<?php

namespace App\Http\Controllers\Web;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Validator;
use Auth;
use Log;
use Session;
use Storage;

use SEO;
use LaravelLocalization;

class SubcategoryController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        
        //** $this->middleware('auth');

        //** all you want to share
        //** view()->share('var', 'value');
        
        view()->share('review_type', 'subcategory');
    }

    public function index ($foo, $subcategoryslug) {

        // controllo se lo slug si riferisce ad un prodotto
        // questo è un hack per le categorie che hanno una sola sottocategorie
        // es. http://invitidesign.it/coni-portariso/coni-portariso/cono5
        $data['page'] = \App\Product::leftJoin('lab_products_translations', 'lab_products.id', '=', 'lab_products_translations.product_id')
                            ->where('lab_products_translations.murl', '=', $subcategoryslug)
                            ->select('lab_products.*')
                            ->first();
        if ($data['page']) {
            $prController = new ProductController();
            return $prController->show('', '', $subcategoryslug);
        }

        $data['show_subcategory'] = true;

        $data['page'] = \App\Subcategory::leftJoin('lab_subcategories_translations', 'lab_subcategories.id', '=', 'lab_subcategories_translations.subcategory_id')
            ->where('murl', '=', $subcategoryslug)
            ->select('lab_subcategories.*')
            ->first();
        if (!$data['page']) abort(404);

        if (!Session::has('breadcrumb_cat'))
            Session::put('breadcrumb_cat', $data['page']->category);
        
        Session::put('breadcrumb_subcat', $data['page']);
        Session::forget('breadcrumb_prod');

    	//** for paging
        $data['arrElements'] = \App\Product::active()->where('type', '=', $data['page']->id)->orderBy('order')->paginate(12);

        //**** SEO ****//
        SEO::setTitle($data['page']->mtitle);
        SEO::setDescription($data['page']->mdescription);
        SEO::opengraph()->setUrl(url()->current());        
        SEO::opengraph()->addProperty('locale', LaravelLocalization::getCurrentLocaleRegional());
        if ($data['page']->img)
            SEO::opengraph()->addImage(img($data['page'], 'img'));

        $data['catToShow'] = $data['page']->type;
    	return view()->make('web.ecommerce.category.index', $data);
    }
}
