<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;

use Mail;
use App\Mail\AccountActivation;
use Log;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/sended/verification';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
		$data['page'] = page('noindex');
        view()->share('page', $data['page']);
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
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
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|min:6|confirmed',
            'check_privacy' => 'required'
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return User
     */
    protected function create(array $data)
    {
        //Log::info($data);

        $u = new \App\User;
        $u->name = $data['name'];
        $u->lastname = $data['lastname'];
        $u->email = $data['email'];
        $u->password = bcrypt($data['password']);
        $u->active = '0';
        $u->business = '1';

        $u->newsletter_accepted_at = isset($data['newsletter_accepted_at']) ? \Carbon\Carbon::now() : null;
        $u->cookie_1_accepted_at = isset($data['cookie_1_accepted_at']) ? \Carbon\Carbon::now() : null;
        $u->cookie_2_accepted_at = isset($data['cookie_2_accepted_at']) ? \Carbon\Carbon::now() : null;
        $u->cookie_3_accepted_at = isset($data['cookie_3_accepted_at']) ? \Carbon\Carbon::now() : null;
        $u->cookie_4_accepted_at = isset($data['cookie_4_accepted_at']) ? \Carbon\Carbon::now() : null;
        $u->cookie_5_accepted_at = isset($data['cookie_5_accepted_at']) ? \Carbon\Carbon::now() : null;

        $u->verify_token = $this->generateVerifyToken();
        $u->save();

        // logica di registrazione dell'utente business
        $b = new \App\Business;
        $b->businessname = $data['businessname'];
        $b->cf = $data['cf'];
        $b->vat = $data['vat'];
        $b->telephone = $data['telephone'];
        $b->city = @$data['city'];
        $b->political_short_name = @$data['political_short_name'];
        $b->country_short_name = @$data['country_short_name'];
        $b->place_id = @$data['place_id'];
        $b->address = $data['address'];
        $b->street_number = $data['street_number'];
        $b->postal_code = $data['postal_code'];
        $b->id_user = $u->id;
        $b->save();

        // email di verifica
        Mail::to($u->email)->queue(new AccountActivation($u));

        // return $u;
        return new \App\User;
    }

    public function sendverification() {
        return view()->make('auth.sendverification');
    }

    public function verify(Request $request, $token) {        
        // NB: non deve mai esser stato verificato prima
        $data['u'] = \App\User::where('verified', '=', '0') 
                            ->where('verify_token', '=', $token)
                            ->where('email', '=', $request->get('email'))
                            ->first();
        if ($data['u']) {
            $data['u']->active = '1';
            $data['u']->verified = '1';
            $data['u']->verify_token = null;
            $data['u']->save();
        }

        return view()->make('auth.verify', $data);
    }

    private function generateVerifyToken() {
        return md5(time());
    }
    
}
