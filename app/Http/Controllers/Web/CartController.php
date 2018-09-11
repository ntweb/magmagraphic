<?php

namespace App\Http\Controllers\Web;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Validator;
use Auth;
use Log;
use Session;
use Storage;
use Cart;

use Mail;
use App\Mail\OrderReceived;

use SEO;
use LaravelLocalization;

class CartController extends Controller
{
	public $arrSteps = array("checkout"=>false, 
                             "shipment"=>false, 
                             "payment"=>false, 
                             "summary"=>false, 
                             "do_payment"=>false, 
                             "finish"=>false);

    public function __construct()
    {
        parent::__construct();    	

        $data['page'] = page('noindex');
		view()->share('page', $data['page']);

        //**** SEO ****//
        SEO::setTitle($data['page']->mtitle);
        SEO::setDescription($data['page']->mdescription);
        SEO::opengraph()->setUrl(url()->current());        
        SEO::opengraph()->addProperty('locale', LaravelLocalization::getCurrentLocaleRegional());
        if ($data['page']->img)
            SEO::opengraph()->addImage(img($data['page'], 'img'));        
    }

    // add product
    public function add(Request $request) {
        // validator
        $fieldsToValidate["id"] = "required";
        $fieldsToValidate["qty"] = "required|integer|min:1";
        $fields = $request->except('_token');
        $validator = Validator::make($fields, $fieldsToValidate);
        if (!$validator->fails()) {

        	$p = \App\Product::find($request->get('id'));
            $options['product']['id'] = $p->id;
			$options['product']['code'] = $p->code;
			$options['product']['price'] = $p->discount ? $p->price_discount : $p->price;
			$options['product']['tax'] = $p->tax;
			$options['product']['color'] = $request->get('color', null);
			$options['product']['size'] = $request->get('size', null);

			if ($p->img) {
				$options['product']['thumb'] = img($p, 'img', '100x100');
			}

			Cart::instance('main')->add($p->id,$p->title, $request->get('qty'), $options['product']['price'], $options);

			// delete coupon if add a new product
			Cart::instance('coupon')->destroy();

            return response()->json(array('success' => trans('labels.product_added')));
        }
        
        return response()->json(
                                array(
                                    'error' => trans('labels.error_adding_product'),
                                    //'errorfields' => $validator->messages()
                                )
                            );    	
    }

    public function delete(Request $request, $rowid) {
		Cart::instance('main')->remove($rowid);
		Cart::instance('coupon')->destroy();

		if ($request->has('force_page_refresh'))
			return response()->json(array('location' => action('Web\CartController@checkout')));

		return response()->json(array('location' => null));
    }

    public function coupon(Request $request) {

        if (!$request->has('coupon'))
            return redirect()->action('Web\CartController@checkout')->with('coupon_error', trans('labels.insert_coupon'));

        $c = \App\Coupon::where('code', '=', $request->get('coupon'))->active()->first();
        if (!$c)
            return redirect()->action('Web\CartController@checkout')->with('coupon_error', trans('labels.coupon_not_valid'));

        Cart::instance('coupon')->destroy();
        if ($c->type == 'value')
            $discount = $c->amount * (-1);
        else
            $discount = ((get_cart_total_ivato('main') / 100) * $c->amount) * (-1);

        $options['product']['tax'] = 0;
        $options['product']['price'] = $discount;
        $options['product']['code'] = $request->get('coupon');
        $options['product']['id_coupon'] = $c->id;
        Cart::instance('coupon')->add($c->id,$c->title, 1, $discount, $options);

        return redirect()->action('Web\CartController@checkout');
    }

    public function refresh() {
    	return view()->make('web.cart.widget.cart');
    }

    /**** steps ****/
    public function checkout() {
        $this->set_step('checkout');
        return view()->make('web.cart.checkout');
    }

    public function shipment(Request $request) {
        $this->set_step('shipment');
        Cart::instance('shipment')->destroy();

        if (Cart::instance('main')->count() <= 0)
            return redirect()->action('Web\CartController@checkout');

        if ($request->has('item')) {
            $s = \App\Shipment::active()->where('id', '=', $request->get('item'))->first();
            if (!$s)
                return redirect()->action('Web\CartController@shipment');

            $options['product']['tax'] = $s->tax;
            $options['product']['price'] = $s->price;
            $options['product']['id_shipment'] = $s->id;
            $options['product']['type'] = $s->type;


            Cart::instance('shipment')->add($s->id,$s->title, 1, $s->price, $options);
            return redirect()->action('Web\CartController@payment');
        }
        
        $data['arrShipment'] = \App\Shipment::active()->get();
        return view()->make('web.cart.shipment', $data);
    }

    public function payment(Request $request) {
    	$this->set_step('payment');
        Cart::instance('payment')->destroy();

        if (Cart::instance('shipment')->count() <= 0)
            return redirect()->action('Web\CartController@shipment');

        if ($request->has('item')) {
            $s = \App\Payment::active()->where('id', '=', $request->get('item'))->first();
            if (!$s)
                return redirect()->action('Web\CartController@payment');

            if ($s->amount_type == 'percent')
                $s->amount = (get_cart_total_ivato('main') + get_cart_total_ivato('coupon') + get_cart_total_ivato('shipment')) / 100 * $s->amount;

            $options['product']['tax'] = $s->tax;
            $options['product']['price'] = $s->amount;
            $options['product']['id_payment'] = $s->id;
            $options['product']['type'] = $s->type;

            Cart::instance('payment')->add($s->id,$s->title, 1, $s->amount, $options);
            return redirect()->action('Web\CartController@summary');
        }

        $data['arrPayment'] = \App\Payment::active()->get();
    	return view()->make('web.cart.payment', $data);
    }

    public function summary() {
        $this->set_step('summary');
        return view()->make('web.cart.summary');
    }

    public function store(Request $request) {
        
        $fieldsToValidate["name"] = "required";
        $fieldsToValidate["lastname"] = "required";
        $fieldsToValidate["telephone"] = "required";
        $fieldsToValidate["city"] = "required";
        $fieldsToValidate["address"] = "required";
        $fieldsToValidate["street_number"] = "required";
        $fieldsToValidate["postal_code"] = "required";
        
        $this->validate($request, $fieldsToValidate);

        // validation passed ...
        // salvo completamente il carrello

        // Tipologie
        $el = \App\Parameter::where('module', '=', 'type')->where('label', '=', 'orderstatus')->first();
        $arrType = explode(',', $el->value);

        $o = new \App\Order;
        $o->id_user = Auth::user()->id;
        $o->type = $arrType[0]; // received

        if (Cart::instance('coupon')->count()){
            $row = Cart::instance('coupon')->content()->first();
            $o->id_coupon = $row->options->product['id_coupon'];
        }

        $row = Cart::instance('payment')->content()->first();
        $o->payment_type = $row->options->product['type'];

        $o->shipment_name = $request->get('lastname').' '.$request->get('name');
        $o->shipment_businessname = $request->get('businessname', null);
        $o->shipment_cf = $request->get('cf', null);
        $o->shipment_vat = $request->get('vat', null);
        $o->shipment_telephone = $request->get('telephone', null);
        $o->shipment_city = $request->get('city', null);
        $o->shipment_political_short_name = $request->get('political_short_name', null);
        $o->shipment_country_short_name = $request->get('country_short_name', null);
        $o->shipment_country_short_name = $request->get('country_short_name', null);
        $o->shipment_address = $request->get('address', null);
        $o->shipment_street_number = $request->get('street_number', null);
        $o->shipment_postal_code = $request->get('postal_code', null);
        $o->shipment_note = $request->get('note', null);

        $o->total = get_cart_total_ivato('main') 
                    + Cart::instance('coupon')->subtotal 
                    + get_cart_total_ivato('shipment') 
                    + get_cart_total_ivato('payment');

        $o->save();

        // save label
        $o->label = env('MAIL_SITE_NAME')." - ".trans('labels.cart')." #".date('Y')."-".$o->id;
        $o->save();

        // salvo le righe
        foreach (Cart::instance('main')->content() as $k => $row) {
            $or = new \App\Orderrow;
            $or->id_order = $o->id;
            $or->id_el = $row->options->product['id'];
            $or->type = 'product';
            $or->row_title = $row->name;

            $code = $row->options->product['code'] ? "cod: ". $row->options->product['code'] . " " : "";
            $color = $row->options->product['color'] ? "color: ". $row->options->product['color'] : "color: - ";
            $size = $row->options->product['size'] ? "size: ". $row->options->product['size'] : "size: - ";
            $or->row_options = $code."[".$color.", ".$size."]";
            $or->row_price = $row->options->product['price'];
            $or->row_qty = $row->qty;
            $or->row_tax = $row->options->product['tax'];
            $or->row_taxable = iva($row->options->product['price']*$row->qty, $row->options->product['tax']);
            $or->row_subtotal = ivato($row->options->product['price']*$row->qty, $row->options->product['tax']);

            $or->save();
        }

        foreach (Cart::instance('coupon')->content() as $k => $row) {
            $or = new \App\Orderrow;
            $or->id_order = $o->id;
            $or->id_el = $row->options->product['id_coupon'];
            $or->type = 'coupon';
            $or->row_title = $row->name." : ".$row->options->product['code'];
            $or->row_price = $row->options->product['price'];
            $or->row_qty = $row->qty;
            $or->row_tax = $row->options->product['tax'];
            $or->row_taxable = iva($row->options->product['price']*$row->qty, $row->options->product['tax']);
            $or->row_subtotal = ivato($row->options->product['price']*$row->qty, $row->options->product['tax']);

            $or->save();

            // se c'è un coupon single-user lo disattivo
            $c = \App\Coupon::find($or->id_el);
            if (!$c->multipleusers){
                $c->active = '0';
                $c->save();
            }
        }

        foreach (Cart::instance('shipment')->content() as $k => $row) {
            $or = new \App\Orderrow;
            $or->id_order = $o->id;
            $or->id_el = $row->options->product['id_shipment'];
            $or->type = 'shipment';
            $or->row_title = $row->name;
            $or->row_price = $row->options->product['price'];
            $or->row_qty = $row->qty;
            $or->row_tax = $row->options->product['tax'];
            $or->row_taxable = iva($row->options->product['price']*$row->qty, $row->options->product['tax']);
            $or->row_subtotal = ivato($row->options->product['price']*$row->qty, $row->options->product['tax']);

            $or->save();

            $o->id_shipment = $or->id_el;
            $o->save();            
        }

        foreach (Cart::instance('payment')->content() as $k => $row) {
            $or = new \App\Orderrow;
            $or->id_order = $o->id;
            $or->id_el = $row->options->product['id_payment'];
            $or->type = 'payment';
            $or->row_title = $row->name;
            $or->row_price = $row->options->product['price'];
            $or->row_qty = $row->qty;
            $or->row_tax = $row->options->product['tax'];
            $or->row_taxable = iva($row->options->product['price']*$row->qty, $row->options->product['tax']);
            $or->row_subtotal = ivato($row->options->product['price']*$row->qty, $row->options->product['tax']);

            $or->save();

            $o->id_payment = $or->id_el;
            $o->save();
        }

        Cart::instance('main')->destroy();
        Cart::instance('coupon')->destroy();
        Cart::instance('shipment')->destroy();
        Cart::instance('payment')->destroy();

        // send order email
        Mail::to(Auth::user()->email)
                ->cc(param('site_email_admin'))
                ->queue(new OrderReceived(Auth::user(), $o));

        return redirect()->action('Web\CartController@doPayment', ['id'=>$o->id]);
    }

    public function doPayment($order_id) {
        $this->set_step('do_payment');

        $o = \App\Order::find($order_id);
        if (!$o)
            return redirect()->action('Web\CartController@error')->with('error', trans('labels.cart_not_found'));

        // controllo prima se il carrello è stato pagato        
        if ($o->paid)
            return redirect()->action('Web\CartController@error')->with('error', trans('labels.cart_already_paid'));

        // controllo se il carrello è dell' utente collegato
        if ($o->id_user != Auth::user()->id)
            return redirect()->action('Web\CartController@error')->with('error', trans('labels.cart_not_valid'));

        switch ($o->payment_type) {
            case 'paypal':
                // se il pagamento è con paypal faccio il redirect al controller di paypal
                return redirect()->action('Web\PaypalController@pay', ['id' => $o->id]);
                break;
            
            default:
                // se il pagamento è in contrassegno vado a finish
                // se il pagamento è in bonifico bancario vado a finish con il riepilogo dei dati di bonifico
                $payment = \App\Payment::find($o->id_payment);
                return redirect()->action('Web\CartController@finish')
                                    ->with('message', $payment->consumer_message)
                                    ->with('cart_label', $o->label);

                break;
        }


        return view()->make('web.cart.payment');
    }

    public function error() {
        return view()->make('web.cart.error');
    }

    public function finish(Request $request) {
        $this->set_step('finish');
        $data = array();

        if ($request->has('id')){
            $data['order'] = \App\Order::find($request->get('id'));
        }

        return view()->make('web.cart.finish', $data);
    }

	private function set_step($key) {
		foreach ($this->arrSteps as $k => $value) {
			$this->arrSteps[$k] = true;
			if ($k == $key) {
				break;
			}
		}

		view()->share('arrSteps', $this->arrSteps);
	}    
}
