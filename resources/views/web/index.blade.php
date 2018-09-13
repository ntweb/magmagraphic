<!DOCTYPE html>
<html lang="{{App::getLocale()}}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

	@if (blockUrl(@$page))
        <meta name="robots" content="noindex">
    @endif
	
    {!! SEO::generate(true) !!}

    @if (env('MINIFY_HTML'))
        @if(isset($_above_the_fold_css))
            <style>{!! $_above_the_fold_css !!}</style>
        @endif
    @else

        <!-- Bootstrap -->
        <link href="{{url('css/bootstrap.min.css')}}" rel="stylesheet">

        <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->


        {{-- FontAwesome Preferred distr --}}
        <link href="{{url('css/font-awesome.min.css')}}" rel="stylesheet">
        {{-- Alert --}}
        <link rel="stylesheet" href="{{url('css/alertify.core.css')}}" />
        <link rel="stylesheet" href="{{url('css/alertify.default.css')}}" />
        {{-- My Site Style--}}
        <link rel="stylesheet" href="{{url('css/_site_style.css')}}" />

    @endif

</head>
<body>


<nav class="navbar navbar-inverse navbar-fixed-top">
    <div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
        </div>
        <div id="navbar" class="collapse navbar-collapse">
            <ul class="nav navbar-nav">
                <?php $rname = Route::currentRouteName(); ?>
                <?php $type = isset($page->type) ? $page->type : null; ?>
                <?php $module = isset($page->module) ? $page->module : null; ?>

                <li @if($rname == 'home') class="active" @endif><a href="{{action('Web\HomepageController@index')}}">Home</a></li>
                <li @if($rname == 'news') class="active" @endif><a href="{{action('Web\NewsController@index')}}">News</a></li>
                <li @if($rname == 'blog') class="active" @endif><a href="{{action('Web\BlogController@index')}}">Blog</a></li>
                <li><a href="{{action('Web\PageController@show', array(1, 'titolo-della-pagina'))}}">Pagina</a></li>
                <li @if($rname == 'service') class="active" @endif><a href="{{action('Web\ServiceController@index')}}">Servizi</a></li>
                <li @if($rname == 'staff') class="active" @endif><a href="{{action('Web\StaffController@show', array(1, 'titolo-della-persona'))}}">Staff</a></li>
                <li @if($rname == 'photogallery') class="active" @endif><a href="{{action('Web\PhotogalleryController@index')}}">Photo</a></li>
                <li @if($rname == 'videogallery') class="active" @endif><a href="{{action('Web\VideogalleryController@index')}}">Video</a></li>
                <li @if($rname == 'portfolio') class="active" @endif><a href="{{action('Web\PortfolioController@index')}}">Portfolio</a></li>

                <li class="dropdown @if($rname == 'category') active @endif">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Catalogue <span class="caret"></span></a>
                    <ul class="dropdown-menu">
                        @foreach ($arrCategories as $c)
                            <li><a href="{{ cat_url($c) }}">{{$c->title}}</a></li>
                        @endforeach
                    </ul>
                </li>

                <li @if($rname == 'contact') class="active" @endif><a href="{{action('Web\ContactController@index')}}">Contact</a></li>

                @if (Auth::check())
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">{{Auth::user()->name}} <span class="caret"></span></a>
                        <ul class="dropdown-menu">
                            <li><a href="{{ action('Web\OrderController@index') }}">I miei ordini</a></li>
                            <li><a href="{{ url('/logout') }}">Logout</a></li>
                        </ul>
                    </li>
                @else
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Account <span class="caret"></span></a>
                        <ul class="dropdown-menu">
                            <li><a href="{{ url('/login') }}">Login</a></li>
                            <li><a href="{{ url('/register') }}">Register</a></li>
                        </ul>
                    </li>
                @endif
            </ul>

            <ul class="nav navbar-nav navbar-right">
                @if (!isset($route_loalization_resource))
                    <li><a href="{{ LaravelLocalization::getLocalizedURL('it') }}">Ita no ris</a></li>
                    <li><a href="{{ LaravelLocalization::getLocalizedURL('en') }}">Eng no ris</a></li>
                @else
                    <li><a href="{{ LaravelLocalization::getURLFromRouteNameTranslated('it', $route_loalization_resource, $route_loalization_resource_param['it']) }}">Ita ris</a></li>
                    <li><a href="{{ LaravelLocalization::getURLFromRouteNameTranslated('en', $route_loalization_resource, $route_loalization_resource_param['en']) }}">Eng ris</a></li>
                @endif
            </ul>

        </div>
    </div>
</nav>

<br>
<br>
<br>
<br>

{{-- cart dynamic widget --}}
<div data-cart-widget="cart_refresh" data-route="{{action('Web\CartController@refresh')}}" class="well">
    @include('web.cart.widget.cart')
</div>

@yield('content')

<hr>

@include('web.newsletter.index')

@include('web.cookie.index')

{{-- Analytics cookie --}}
@if (!Session::has('cookie_2_accepted'))
    <script type="text/javascript">

    </script>
@endif

{{-- Marketing cookie --}}
@if (!Session::has('cookie_3_accepted'))
    <script type="text/javascript">

    </script>
@endif

@if (env('MINIFY_HTML'))
    <script src="{{url('minify/code.min.js')}}" defer></script>
@else
    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="{{url('js/jquery-1.12.4.js')}}"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="{{url('js/bootstrap.min.js')}}"></script>

    {{-- my library --}}
    @if($rname == 'contact' || $rname == 'register' || $rname == 'account' || $rname == 'cart')
        <script src="https://maps.googleapis.com/maps/api/js?key={{env('GOOGLE_PLACE_API_KEY')}}&libraries=places" {{--defer--}}></script>
    @endif

    <script src="{{url('js/geocomplete/jquery.geocomplete.min.js')}}"></script>
    <script src="{{url('js/alertify/alertify.min.js')}}"></script>
    <script src="{{url('js/_site_library.js')}}"></script>
    <script src="{{url('js/_site_cart.js')}}"></script>

    <script src="{{url('js/cookie/cookie.js')}}"></script>



@endif

</body>
</html>