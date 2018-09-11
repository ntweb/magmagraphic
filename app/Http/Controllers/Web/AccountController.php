<?php

namespace App\Http\Controllers\Web;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;

class AccountController extends Controller
{

    public function __construct()
    {
        parent::__construct();
        
        //** $this->middleware('auth');

        //** all you want to share
        //** view()->share('var', 'value');
        
        view()->share('hide_menu', true);        
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
    public function edit()
    {
        return view()->make('auth.register');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|max:255',
            'lastname' => 'required|max:255',
            // 'businessname' => 'required|max:255',
            'cf' => 'required|max:255',
            // 'vat' => 'required|max:255',
            'telephone' => 'required|max:255',
//            'city' => 'required|max:255',
            'address' => 'required|max:255',
            'street_number' => 'required|max:255',
            'postal_code' => 'required|max:255',
        ]);

        $data = $request->all();

        $u = Auth::user();
        $u->name = $data['name'];
        $u->lastname = $data['lastname'];

        $u->newsletter_accepted_at = isset($data['newsletter_accepted_at']) ? \Carbon\Carbon::now() : null;
        $u->cookie_1_accepted_at = isset($data['cookie_1_accepted_at']) ? \Carbon\Carbon::now() : null;
        $u->cookie_2_accepted_at = isset($data['cookie_2_accepted_at']) ? \Carbon\Carbon::now() : null;
        $u->cookie_3_accepted_at = isset($data['cookie_3_accepted_at']) ? \Carbon\Carbon::now() : null;
        $u->cookie_4_accepted_at = isset($data['cookie_4_accepted_at']) ? \Carbon\Carbon::now() : null;
        $u->cookie_5_accepted_at = isset($data['cookie_5_accepted_at']) ? \Carbon\Carbon::now() : null;

        $u->save();

        $b = $u->b;
        $b->businessname = $data['businessname'];
        $b->cf = $data['cf'];
        $b->vat = $data['vat'];
        $b->telephone = $data['telephone'];
        $b->city = @$data['city'];
        $b->political_short_name = @$data['political_short_name'];
        $b->country_short_name = @$data['country_short_name'];
        $b->place_id = @$data['place_id'];
        $b->address = @$data['address'];
        $b->street_number = $data['street_number'];
        $b->postal_code = $data['postal_code'];
        $b->id_user = $u->id;
        $b->save();        

        return view()->make('auth.register');   
    }

    public function credential(Request $request)
    {
        $this->validate($request, [
            'password' => 'required|min:6|confirmed',
        ]);

        $data = $request->all();

        $u = Auth::user();
        $u->password = bcrypt($data['password']);
        $u->save();        

        return view()->make('auth.register');   
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
