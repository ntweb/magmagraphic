<?php

namespace App\Http\Controllers\Web;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Validator;
use Auth;
use Log;
use Session;
use Storage;
use App;

use SEO;
use LaravelLocalization;

class ProductController extends Controller
{

    public function __construct()
    {
        parent::__construct();
        
        //** $this->middleware('auth');

        //** all you want to share
        //** view()->share('var', 'value');
        
        view()->share('review_type', 'products');
    }

    public function index(Request $request)
    {            
        $data['show_search'] = true;

        if ($request->has('key')) {
            $query = \App\Product::leftJoin('lab_products_translations', 'lab_products.id', '=', 'lab_products_translations.product_id')
                                    ->where('lab_products.active', '=', '1')
                                    ->where('lab_products_translations.locale', '=', App::getLocale())
                                    ->where(function ($query) use ($request) {
                                        $query->where('lab_products.code', '=', $request->get('key'));
                                        $query->orWhere(function ($query) use ($request) {
                                            $keys = explode(' ', $request->get('key'));
                                            foreach ($keys as $v) {
                                                if (trim($v) != '')
                                                    $query->where('title', 'LIKE', '%'.$v.'%');
                                            }
                                        });
                                    })->select('lab_products.*');
        }

        // filter type
        if ($request->has('type')) {
            $query->where('type', '=', $request->get('type'));
            $data['type'] = \App\Subcategory::find($request->get('type'));
        }

        $request->flash();

        $data['arrElements'] = $query->paginate(50);

        // param pagination
        $data['pagination_param']['key'] = $request->get('key', null);
        $data['pagination_param']['type'] = $request->get('type', null);           
        return view()->make('web.ecommerce.category.index', $data);
    }

    public function show($foo, $bar, $productslug) {

        $data['show_prod_detail'] = true;
        $data['page'] = \App\Product::leftJoin('lab_products_translations', 'lab_products.id', '=', 'lab_products_translations.product_id')
            ->where('lab_products_translations.murl', '=', $productslug)
            ->select('lab_products.*')
            ->first();
    	if (!$data['page']) abort(404);

        Session::put('breadcrumb_cat', $data['page']->category);
        Session::put('breadcrumb_subcat', $data['page']->subcategory);
        Session::put('breadcrumb_prod', $data['page']);

        $data['page_images'] = $data['page']->attachments_images()->get();
        $data['page_docs'] = $data['page']->attachments_docs()->get();

        $data['page_reviews'] = $data['page']->reviews()->get();
        $data['options_colors'] = $data['page']->options_colors()->get();
        $data['options_sizes'] = $data['page']->options_sizes()->get();
        
        //**** SEO ****//
        SEO::setTitle($data['page']->mtitle);
        SEO::setDescription($data['page']->mdescription);
        SEO::opengraph()->setUrl(url()->current());        
        SEO::opengraph()->addProperty('locale', LaravelLocalization::getCurrentLocaleRegional());
        if ($data['page']->img)
            SEO::opengraph()->addImage(img($data['page'], 'img'));

        $data['catToShow'] = $data['page']->id_category;
    	return view()->make('web.ecommerce.category.index', $data);
    }
}
