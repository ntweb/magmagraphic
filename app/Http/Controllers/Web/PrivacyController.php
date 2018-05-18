<?php

namespace App\Http\Controllers\Web;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Log;

use SEO;
use LaravelLocalization;

class PrivacyController extends Controller
{

    public function index () {
    	$page = page('privacy', '0');
    	if (!$page) abort(404);

        //**** SEO ****//
        SEO::setTitle($page->mtitle);
        SEO::setDescription($page->mdescription);
        SEO::opengraph()->setUrl(url()->current());
        SEO::opengraph()->addProperty('locale', LaravelLocalization::getCurrentLocaleRegional());   
        if ($page->img)
            SEO::opengraph()->addImage(img($page, 'img'));

        /**** sostituzioni ****/
        $_description = $page->description;
        $_description = str_replace('#gdpr_site_name#', param('gdpr_site_name'), $_description);
        $_description = str_replace('#gdpr_site_url#', param('gdpr_site_url'), $_description);
        $_description = str_replace('#gdpr_tit_trat#', param('gdpr_tit_trat'), $_description);
        $_description = str_replace('#gdpr_email_titolare#', param('gdpr_email_titolare'), $_description);
        $_description = str_replace('#gdpr_tit_trat_2#', param('gdpr_tit_trat_2'), $_description);
        $_description = str_replace('#gdpr_site_name#', param('gdpr_site_name'), $_description);
        $_description = str_replace('#gdpr_luogo_trat#', param('gdpr_luogo_trat'), $_description);
        $_description = str_replace('#gdpr_dati_finalita_desc#', param('gdpr_dati_finalita_desc', 'extras'), $_description);
        $_description = str_replace('#gdpr_dati_periodo#', param('gdpr_dati_periodo'), $_description);
        $_description = str_replace('#gdpr_url#', param('gdpr_url'), $_description);
        $_description = str_replace('#gdpr_data_modifica#', param('gdpr_data_modifica'), $_description);

        $page->description = $_description;
        $data['page'] = $page;
        return view()->make('web.page.show', $data);
    }


}
