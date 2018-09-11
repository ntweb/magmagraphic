@extends('web.index')

@section('content')


    @if(Auth::check())
        Modifica account
    @else
        Regitrazione nuovo account
    @endif


    @if(Auth::check())
    <h2>Modifica account</h2>                                        
    @else
    <h2>Regitrazione nuovo account</h2>                                        
    @endif

    <form class="ns-disable-submit" role="form" method="POST" action="@if(Auth::check()) {{action('Web\AccountController@update')}} @else {{ route('register') }} @endif">
    {{ csrf_field() }}

    <label>Nome *</label><br />
    <input type="text" class="form-control" name="name" value="{{ old('name', @Auth::user()->name) }}" autofocus>
    @if ($errors->has('name'))
        <span class="label label-danger">
            <strong>{{ $errors->first('name') }}</strong>
        </span>
    @endif                                                                                                        

    <label>Cognome *</label><br />
    <input type="text" class="form-control" name="lastname" value="{{ old('lastname', @Auth::user()->lastname) }}" autofocus>
    @if ($errors->has('lastname'))
        <span class="label label-danger">
            <strong>{{ $errors->first('lastname') }}</strong>
        </span>
    @endif                                                                                                        

    <label>Ragione sociale</label><br />
    <input type="text" class="form-control" name="businessname" value="{{ old('businessname', @Auth::user()->b->businessname) }}" autofocus>

    @if ($errors->has('businessname'))
        <span class="label label-danger">
            <strong>{{ $errors->first('businessname') }}</strong>
        </span>
    @endif

    <label>Cod. Fiscale *</label><br />
    <input type="text" class="form-control" name="cf" value="{{ old('cf', @Auth::user()->b->cf) }}" autofocus>
    @if ($errors->has('cf'))
        <span class="label label-danger">
            <strong>{{ $errors->first('cf') }}</strong>
        </span>
    @endif                                                                                                        

    <label>P. IVA</label><br />
    <input type="text" class="form-control" name="vat" value="{{ old('vat', @Auth::user()->b->vat) }}" autofocus>
    @if ($errors->has('vat'))
        <span class="label label-danger">
            <strong>{{ $errors->first('vat') }}</strong>
        </span>
    @endif                                                                                                        

    <label>Telefono</label><br />
    <input type="text" class="form-control" name="telephone" value="{{ old('telephone', @Auth::user()->b->telephone) }}" autofocus>
    @if ($errors->has('telephone'))
        <span class="label label-danger">
            <strong>{{ $errors->first('telephone') }}</strong>
        </span>
    @endif

    <label>Città *</label><br />
    <input type="hidden" name="political_short_name" value="{{ old('political_short_name', @Auth::user()->b->political_short_name) }}">
    <input type="hidden" name="country_short_name" value="{{ old('country_short_name', @Auth::user()->b->country_short_name) }}">
    <input type="hidden" name="place_id" value="{{ old('place_id', @Auth::user()->b->place_id) }}">
    <input type="text" class="form-control" name="city" value="{{ old('city', @Auth::user()->b->city) }}" autofocus>
    @if ($errors->has('city'))
        <span class="label label-danger">
            <strong>{{ $errors->first('city') }}</strong>
        </span>
    @endif                                                                                                        

    <label>CAP *</label><br />
    <input type="text" class="form-control" name="postal_code" value="{{ old('postal_code', @Auth::user()->b->postal_code) }}" autofocus>
    @if ($errors->has('postal_code'))
        <span class="label label-danger">
            <strong>{{ $errors->first('postal_code') }}</strong>
        </span>
    @endif                                                                                                        

    <label>Indirizzo *</label><br />
    <input type="text" class="form-control" name="address" value="{{ old('address', @Auth::user()->b->address) }}" autofocus>
    @if ($errors->has('address'))
        <span class="label label-danger">
            <strong>{{ $errors->first('address') }}</strong>
        </span>
    @endif                                                                                                        

    <label>N° *</label><br />
    <input type="text" class="form-control" name="street_number" value="{{ old('street_number', @Auth::user()->b->street_number) }}" autofocus>
    @if ($errors->has('street_number'))
        <span class="label label-danger">
            <strong>{{ $errors->first('street_number') }}</strong>
        </span>
    @endif

    <h2>Newsletter</h2>
    <div class="checkbox">
        <label>
            <input type="checkbox" value="1" name="newsletter_accepted_at" @if(@Auth::user()->newsletter_accepted_at) checked @endif>
            {{ trans('web.newsletter_acceptance') }} {{ param('gdpr_site_name') }}
        </label>
    </div>

    <h2>Cookie, Privacy e trattamento dei dati</h2>

    @foreach($arrCookies as $c)
    <?php $_cookie = 'cookie_'.$c->id.'_accepted_at'; ?>
    @if($c->id <> 1)
    <div class="checkbox">
        <label>
            <input type="checkbox" value="{{ $c->id }}" name="{{ $_cookie }}" @if(@Auth::user()->$_cookie) checked @endif>
            {{ trans('web.cookie_acceptance') }} {{ $c->title }}: {{ $c->description }}
        </label>
    </div>
    @endif
    @endforeach

    @if(!Auth::user())
    <div class="checkbox">
        <label>
            <input type="checkbox" value="1" name="check_privacy">
            {!! trans('web.privacy_check') !!}
        </label>
        @if ($errors->has('check_privacy'))
            <span class="label label-danger">
                <strong>{{ $errors->first('check_privacy') }}</strong>
            </span>
        @endif
    </div>
    @endif


        {{-- sto in fase di registrazione di un nuovo account --}}
    @if (!Auth::check())
        <h2>Dati di login</h2>


        <label>Email *</label><br />
        <input type="text" class="form-control" name="email" value="{{ old('email', @Auth::user()->email) }}" autofocus>
        @if ($errors->has('email'))
            <span class="label label-danger">
                <strong>{{ $errors->first('email') }}</strong>
            </span>
        @endif

        <label>Password *</label><br />
        <input type="password" class="form-control" name="password" value="" autofocus>
        @if ($errors->has('password'))
            <span class="label label-danger">
                <strong>{{ $errors->first('password') }}</strong>
            </span>
        @endif                                                                                                        

        <label>Conferma password *</label><br />
        <input type="password" class="form-control" name="password_confirmation" value="" autofocus>
        @if ($errors->has('password_confirmation'))
            <span class="label label-danger">
                <strong>{{ $errors->first('password_confirmation') }}</strong>
            </span>
        @endif                                                                                                        
    @endif                                         

    <div class="form-group clearfix">
        <p><input type="submit" value="Invia" class="wpcf7-form-control wpcf7-submit" /></p>
    </div>
</form>

{{-- sto in fase di aggiornamento delle informazioni --}}
@if (Auth::check())
        <h2>Login</h2>

        <form class="ns-disable-submit" role="form" method="POST" action="{{ action('Web\AccountController@credential') }}">
        {{ csrf_field() }}

            <label>Email</label><br />
            {{ Auth::user()->email }}

                <label>Password *</label><br />
                <input type="password" class="form-control" name="password" value="" autofocus>
                @if ($errors->has('password'))
                    <span class="label label-danger">
                        <strong>{{ $errors->first('password') }}</strong>
                    </span>
                @endif                                                                                                        

                <label>Conferma password *</label><br />
                <input type="password" class="form-control" name="password_confirmation" value="" autofocus>
                @if ($errors->has('password_confirmation'))
                    <span class="label label-danger">
                        <strong>{{ $errors->first('password_confirmation') }}</strong>
                    </span>
                @endif                                                                                                        
            
                <p><input type="submit" value="Invia" class="wpcf7-form-control wpcf7-submit" /></p>
        </form>
    </div>
@endif

@endsection
