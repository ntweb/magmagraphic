<?php

namespace App\Http\Controllers\Lab;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Validator;
use Auth;
use Log;
use Session;
use Storage;

class SubcategoryController extends Controller
{

    protected $uploadfolder = 'subcategories';
    protected $arrType;
    protected $default_lang;

    public function __construct()
    {
        parent::__construct();
        $this->middleware('auth');

        view()->share('table', 'lab_subcategories');
        view()->share('uploadfolder', $this->uploadfolder);
        

        \LaravelLocalization::setLocale('it');
        $this->default_lang = config('laravellocalization.supportedLocales.it');
        view()->share('default_lang', $this->default_lang);

        view()->share('mod_name', 'Sotto categorie');
        view()->share('mod_action', 'Lista');
        view()->share('mod_object', 'Sotto categorie');

        // Categorie
        $this->arrType = \App\Category::whereHas('translations', function ($query) {
                                                    $query->where('locale', 'it')
                                                    ->orderBy('title');
                                                })->get();

        view()->share('arrType', $this->arrType);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
            
        // for back button
        Session::put('backurl', $request->fullUrl());
        $data['route_search'] = action('Lab\SubcategoryController@index');

        if ($request->has('key'))
            $query = \App\Subcategory::whereHas('translations', function ($query) use ($request) {
                                $query->where('locale', 'it')
                                ->where('title', 'LIKE', '%'.$request->get('key').'%')
                                ->orWhere('subcategory_id', '=', $request->get('key'));
                            });
        else
            $query = \App\Subcategory::orderBy('order')->orderBy('id', 'desc');


        // filter type
        if ($request->has('type')) {
            $query->where('type', '=', $request->get('type'));
            $data['type'] = \App\Category::find($request->get('type'));
        }

        $data['arrElements'] = $query->paginate(50);
        return view()->make('lab.subcategory.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data['mod_action'] = 'Crea nuovo elemento';
        $data['mod_object'] = 'Sotto categoria';

        $data['back'] = action('Lab\SubcategoryController@index');
        $data['route'] = action('Lab\SubcategoryController@store');

        return view()->make('lab.subcategory.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // validator
        $fieldsToValidate["title"] = "required";

        $fields = $request->except('_token');
        $validator = Validator::make($fields, $fieldsToValidate);
        if (!$validator->fails()) {
            $el = new \App\Subcategory;
            foreach ($fields as $key => $value) {
                $el->$key = $value;

                // mtitle and murl
                if ($key == 'title') {
                    $el->translateOrNew($request->get('lang'))->mtitle = $value;                
                    $el->translateOrNew($request->get('lang'))->murl = str_slug($value);                
                }
               
            }

            // default 
            $el->uploadfolder = $this->uploadfolder;

            $el->id_created_by = Auth::user()->id;
            if (!$el->save()){
                return response()->json(array('error' => trans('lab.errore-sql')));
            }            

            $result['id'] = $el->id;
            $result['route'] = action('Lab\SubcategoryController@edit', array($el->id));

            return response()->json(array('success' => trans('lab.store_ok'), 'result' => json_encode($result['route'])));
        }
        
        return response()->json(
                                array(
                                    'error' => trans('lab.compilare_campi_obbligatori'),
                                    'errorfields' => $validator->messages()
                                )
                            );
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {

        $data['mod_action'] = 'Modifica';
        $data['mod_object'] = 'Sotto categoria : ID '.$id;

        $data['route'] = action('Lab\SubcategoryController@update', array($id));
        $data['route_settings'] = action('Lab\SubcategoryController@settings', array($id));
        $data['back'] = Session::get('backurl', action('Lab\SubcategoryController@index'));
        $data['el'] = \App\Subcategory::find($id);

        return view()->make('lab.subcategory.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $fieldsToValidate["title"] = "required";

        $fields = $request->except('_token', 'lang');
        $validator = Validator::make($fields, $fieldsToValidate);
        if (!$validator->fails()) {
            $el = \App\Subcategory::find($id);
            foreach ($fields as $key => $value) {
                $el->translateOrNew($request->get('lang'))->$key = $value;

                // murl
                if ($key == 'murl') $el->translateOrNew($request->get('lang'))->murl = str_slug($value);
            }

            $el->id_updated_by = Auth::user()->id;
            if (!$el->save()){
                return response()->json(array('error' => trans('lab.errore-sql')));
            }            

            $result['id'] = $el->id;
            return response()->json(array('success' => trans('lab.store_ok'), 'result' => json_encode($result)));
        }
        
        return response()->json(
                                array(
                                    'error' => trans('lab.compilare_campi_obbligatori'),
                                    'errorfields' => $validator->messages()
                                )
                            );        
    
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $el = \App\Subcategory::find($id);

        Storage::disk('docs')->deleteDirectory($el->uploadfolder.'/'.$el->id);

        $el->delete();
        $result['id'] = $id;
        return response()->json(array('success' => trans('lab.store_ok'), 'result' => json_encode($result)));                
    }

    public function settings(Request $request, $id)
    {
        $fieldsToValidate = array();

        $fields = $request->except('_token');
        $validator = Validator::make($fields, $fieldsToValidate);
        if (!$validator->fails()) {
            $el = \App\Subcategory::find($id);
            foreach ($fields as $key => $value) {

                if ($key == 'type') {
                    // cambio id_category anche ai prodotti
                    if ($el->$key != $value) {
                        \App\Product::where('type', $el->id)
                                    ->update(['id_category' => $value]);
                    }
                }

                $el->$key = $value;

                if ($key == 'begin' && $value) $el->$key = \Carbon\Carbon::createFromFormat($this->default_lang['date'], $value)->toDateString();
            }

            $el->id_updated_by = Auth::user()->id;
            if (!$el->save()){
                return response()->json(array('error' => trans('lab.errore-sql')));
            }            

            // se la sottocategory viene disattivata => disattivo anche i prodotti
            if (!$el->active)
                    \App\Product::where('type', $el->id)->update(['active' => '0']);            

            $result['id'] = $el->id;
            return response()->json(array('success' => trans('lab.store_ok'), 'result' => json_encode($result)));
        }
        
        return response()->json(
                                array(
                                    'error' => trans('lab.compilare_campi_obbligatori'),
                                    'errorfields' => $validator->messages()
                                )
                            );        
    }

    public function deleteImg($id,$img) {
        $el = \App\Subcategory::find($id);

        $storage = $el->uploadfolder.'/'.$el->id.'/';
        $filename = $el->$img;

        Storage::disk('docs')->delete($storage.$filename);

        $el->$img = null;
        $el->save();

        $result['id'] = $el->id;
        return response()->json(array('success' => trans('lab.store_ok'), 'result' => json_encode($result)));        
    }

    public function changeFlag($id, $field) {
        $el = \App\Subcategory::find($id);

        if ($el->$field) $el->$field = '0';
        else $el->$field = '1';

        $el->save();

        if ($field == 'facebookxml') {
            DB::table('lab_products')->whereType($id)->update([
                'facebookxml' => $el->$field
            ]);
        }

        $result['id'] = $el->id;
        $result['flag'] = $el->$field;
        return response()->json(array('success' => trans('lab.store_ok'), 'result' => json_encode($result)));
    }
}
