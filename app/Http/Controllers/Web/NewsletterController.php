<?php

namespace App\Http\Controllers\Web;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Validator;
use Auth;

class NewsletterController extends Controller
{
	
	public function store(Request $request) {
        // validator
        $fieldsToValidate["email"] = "required|email";
        $fieldsToValidate["check_privacy"] = "required";

        $fields = $request->except('_token');
        $validator = Validator::make($fields, $fieldsToValidate);
        if (!$validator->fails()) {
            $el = new \App\Newsletter;
            foreach ($fields as $key => $value) {
                $el->$key = $value;
            }

            try {
            	$el->save();            	
            }
			catch (\PDOException $e) {
				//.. do nothing
			}
            return response()->json(array('pop_up_success' => trans('labels.newsletter_ok')));
        }
        
        return response()->json(
                                array(
                                    // 'error' => trans('labels.compilare_campi_obbligatori'),
                                    'errorfields' => $validator->messages()
                                )
                            );
	}

}
