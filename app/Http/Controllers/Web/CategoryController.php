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

class CategoryController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        
        //** $this->middleware('auth');

        //** all you want to share
        //** view()->share('var', 'value');
        
        view()->share('review_type', 'category');
    }

    public function index ($categoryslug) {

        $data['show_category'] = true;
        $data['page'] = \App\Category::active()
            ->leftJoin('lab_categories_translations', 'lab_categories.id', '=', 'lab_categories_translations.category_id')
            ->where('murl', '=', $categoryslug)
            ->select('lab_categories.*')
            ->first();
        if (!$data['page']) abort(404);

    	Session::put('breadcrumb_cat', $data['page']);
    	Session::forget('breadcrumb_subcat');
    	Session::forget('breadcrumb_prod');

        //**** SEO ****//
        SEO::setTitle($data['page']->mtitle);
        SEO::setDescription($data['page']->mdescription);
        SEO::opengraph()->setUrl(url()->current());        
        SEO::opengraph()->addProperty('locale', LaravelLocalization::getCurrentLocaleRegional());
        if ($data['page']->img)
            SEO::opengraph()->addImage(img($data['page'], 'img'));


        $data['arrSub'] = $data['page']->subcategories()->get();
        $data['catToShow'] = $data['page']->id;

    	return view()->make('web.ecommerce.category.index', $data);
    }    

}
