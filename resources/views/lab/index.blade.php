<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<meta http-equiv="X-UA-Compatible" content="IE=edge">

<title>Artisan Lab - Admin CMS - 2.0</title>

<link rel="shortcut icon" type="image/x-icon" href="{{url('assets/lab/images/favicon.png')}}">

<link rel="stylesheet" href="{{url('assets/lab/css/style.default.css')}}" />
<link rel="stylesheet" href="{{url('assets/lab/css/style.navyblue.css')}}" />
<link rel="stylesheet" href="{{url('assets/lab/css/responsive-tables.css')}}" />
<link rel="stylesheet" href="{{url('assets/lab/css/font-awesome.min.css')}}" />
<link rel="stylesheet" href="{{url('assets/lab/js/plupload/js/jquery.ui.plupload/css/jquery.ui.plupload.css')}}" />
<link rel="stylesheet" href="{{url('assets/lab/css/jquery.googlePreviewSnippet.css')}}" />
<link rel="stylesheet" href="{{url('assets/lab/js/Croppie/croppie.css')}}" >
<link rel="stylesheet" href="{{url('assets/lab/css/style.css')}}" />

<!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!--[if lt IE 9]>
<script src="js/html5shiv.js"></script>
<script src="js/respond.min.js"></script>
<![endif]-->

<script>
    var labassets = '{{url('/assets/lab/')}}';
</script>

<script src="{{url('assets/lab/js/jquery-1.10.2.min.js')}}"></script>
<script src="{{url('assets/lab/js/jquery-migrate-1.2.1.min.js')}}"></script>
<script src="{{url('assets/lab/js/jquery-ui-1.10.3.min.js')}}"></script>
<script src="{{url('assets/lab/js/bootstrap.min.js')}}"></script>
<script src="{{url('assets/lab/js/modernizr.min.js')}}"></script>
<script src="{{url('assets/lab/js/jquery.cookies.js')}}"></script>
<script src="{{url('assets/lab/js/jquery.uniform.min.js')}}"></script>
<script src="{{url('assets/lab/js/flot/jquery.flot.min.js')}}"></script>
<script src="{{url('assets/lab/js/flot/jquery.flot.resize.min.js')}}"></script>
<script src="{{url('assets/lab/js/responsive-tables.js')}}"></script>
<script src="{{url('assets/lab/js/jquery.slimscroll.js')}}"></script>
<script src="{{url('assets/lab/js/jquery.alerts.js')}}"></script>
<script src="{{url('assets/lab/js/jquery.jgrowl.js')}}"></script>
<script src="{{url('assets/lab/js/jquery.tagsinput.min.js')}}"></script>
<script src="{{url('assets/lab/js/plupload/js/plupload.min.js')}}"></script>
<script src="{{url('assets/lab/js/plupload/js/jquery.ui.plupload/jquery.ui.plupload.min.js')}}"></script>
<script src="{{url('assets/lab/js/imagepreview.min.js')}}"></script>
<script src="{{url('assets/lab/js/colorpicker.js')}}"></script>
<script src="{{url('assets/lab/js/jquery.googlePreviewSnippet.js')}}"></script>
<script src="{{url('assets/lab/js/ckeditor/ckeditor.js')}}"></script>
<script src="{{url('assets/lab/js/ckeditor/adapters/jquery.js')}}"></script>
<script src="{{url('assets/lab/js/Croppie/croppie.min.js')}}"></script>
<script src="{{url('assets/lab/js/chosen.jquery.min.js')}}"></script>
<script src="{{url('assets/lab/js/custom.js')}}"></script>
<script src="{{url('assets/lab/js/lab.js')}}"></script>

<!--[if lte IE 8]>
<script src="js/excanvas.min.js"></script>
<![endif]-->

</head>

<body>

<div id="mainwrapper" class="mainwrapper">
    
    <div class="header">
        <div class="logo">
            <a href="{{action('Lab\DashboardController@index')}}"><img src="{{url('assets/lab/images/logo.png')}}" alt="" /></a>
        </div>
        <div class="headerinner">
            <ul class="headmenu">
                <li class="odd">
                    <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                        <span class="count"></span>
                        <span class="head-icon head-message"></span>
                        <span class="headmenu-label">Messages</span>
                    </a>
                    <ul class="dropdown-menu">
                        <li class="nav-header">No messages</li>
                    {{--
                        <li><a href=""><span class="glyphicon glyphicon-envelope"></span> New message from <strong>Jack</strong> <small class="muted"> - 19 hours ago</small></a></li>
                        <li><a href=""><span class="glyphicon glyphicon-envelope"></span> New message from <strong>Daniel</strong> <small class="muted"> - 2 days ago</small></a></li>
                        <li><a href=""><span class="glyphicon glyphicon-envelope"></span> New message from <strong>Jane</strong> <small class="muted"> - 3 days ago</small></a></li>
                        <li><a href=""><span class="glyphicon glyphicon-envelope"></span> New message from <strong>Tanya</strong> <small class="muted"> - 1 week ago</small></a></li>
                        <li><a href=""><span class="glyphicon glyphicon-envelope"></span> New message from <strong>Lee</strong> <small class="muted"> - 1 week ago</small></a></li>
                        <li class="viewmore"><a href="messages.html">View More Messages</a></li>
                    --}}
                    </ul>
                </li>
                <li>
                    <a class="dropdown-toggle" data-toggle="dropdown" data-target="#">
                    <span class="count"></span>
                    <span class="head-icon head-users"></span>
                    <span class="headmenu-label">{{trans('lab.users')}}</span>
                    </a>
                    <ul class="dropdown-menu newusers">
                        <li>
                            <a href="javascript:void(0);" class="get-html" data-route="{{action('Lab\UserController@index')}}">
                                <i class="fa fa-fw fa-angle-right" aria-hidden="true"></i> {{trans('lab.list')}}
                            </a>
                            <a href="javascript:void(0);" class="get-html" data-route="{{action('Lab\UserController@create')}}">
                                <i class="fa fa-fw fa-angle-right" aria-hidden="true"></i> {{trans('lab.create-new')}}
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="odd">
                    <a class="dropdown-toggle" data-toggle="dropdown" data-target="#">
                    <span class="count"></span>
                    <span class="head-icon head-bar"></span>
                    <span class="headmenu-label">Statistics</span>
                    </a>
                    <ul class="dropdown-menu">
                        <li class="nav-header">No statistics</li>
                        {{--
                        <li><a href=""><span class="glyphicon glyphicon-align-left"></span> New Reports from <strong>Products</strong> <small class="muted"> - 19 hours ago</small></a></li>
                        <li><a href=""><span class="glyphicon glyphicon-align-left"></span> New Statistics from <strong>Users</strong> <small class="muted"> - 2 days ago</small></a></li>
                        <li><a href=""><span class="glyphicon glyphicon-align-left"></span> New Statistics from <strong>Comments</strong> <small class="muted"> - 3 days ago</small></a></li>
                        <li><a href=""><span class="glyphicon glyphicon-align-left"></span> Most Popular in <strong>Products</strong> <small class="muted"> - 1 week ago</small></a></li>
                        <li><a href=""><span class="glyphicon glyphicon-align-left"></span> Most Viewed in <strong>Blog</strong> <small class="muted"> - 1 week ago</small></a></li>
                        <li class="viewmore"><a href="charts.html">View More Statistics</a></li>
                        --}}
                    </ul>
                </li>
                <li class="right">
                    <div class="userloggedinfo">

                        @if (Auth::user()->img)
                        <img class="avatar" src="{{img(Auth::user(), 'img')}}" alt="" />
                        @else
                        <img class="avatar" src="{{url('assets/lab/images/photos/thumb1.png')}}" alt="" />
                        @endif

                        <div class="userinfo">
                            <h5>{{Auth::user()->name}} {{Auth::user()->lastname}}<small> - {{Auth::user()->email}}</small></h5>
                            <ul>
                                <li><a href="javascript:void(0);" class="get-html" data-route="{{action('Lab\UserController@edit', array(Auth::user()->id))}}">{{trans('lab.settings')}}</a></li>
                                <li><a href="{{action('Lab\LoginController@logout')}}">{{trans('lab.logout')}}</a></li>
                            </ul>
                        </div>
                    </div>
                </li>
            </ul><!--headmenu-->
        </div>
    </div>
    
    <div class="leftpanel">
        
        <div class="leftmenu">        
            <ul class="nav nav-tabs nav-stacked">
                <li class="nav-header">Navigation</li>

                <li><a href="{{action('Lab\DashboardController@index')}}"><i class="fa fa-fw fa-laptop" aria-hidden="true"></i> Dashboard</a></li>

                <li class="dropdown"><a href=""><i class="fa fa-fw fa-files-o" aria-hidden="true"></i> Pagine</a>
                    <ul>
                        <li><a href="javascript:void(0)" class="get-html" data-route="{{action('Lab\PageController@index')}}">Lista</a></li>
                        <li><a href="javascript:void(0)" class="get-html" data-route="{{action('Lab\PageController@create')}}">Crea nuovo elemento</a></li>
                    </ul>
                </li>

                <li class="dropdown"><a href=""><i class="fa fa-fw fa-newspaper-o" aria-hidden="true"></i> News / Articoli / Blog</a>
                    <ul>
                        <li><a href="javascript:void(0)" class="get-html" data-route="{{action('Lab\NewsController@index')}}">Lista</a></li>
                        <li><a href="javascript:void(0)" class="get-html" data-route="{{action('Lab\NewsController@create')}}">Crea nuovo elemento</a></li>
                    </ul>
                </li>

                <li class="dropdown"><a href=""><i class="fa fa-fw fa-briefcase" aria-hidden="true"></i> Servizi</a>
                    <ul>
                        <li><a href="javascript:void(0)" class="get-html" data-route="{{action('Lab\ServiceController@index')}}">Lista</a></li>
                        <li><a href="javascript:void(0)" class="get-html" data-route="{{action('Lab\ServiceController@create')}}">Crea nuovo elemento</a></li>
                    </ul>
                </li>
                
                <li class="dropdown"><a href=""><i class="fa fa-fw fa-users" aria-hidden="true"></i> Staff</a>
                    <ul>
                        <li><a href="javascript:void(0)" class="get-html" data-route="{{action('Lab\StaffController@index')}}">Lista</a></li>
                        <li><a href="javascript:void(0)" class="get-html" data-route="{{action('Lab\StaffController@create')}}">Crea nuovo elemento</a></li>
                    </ul>
                </li>
                
                <li class="dropdown"><a href=""><i class="fa fa-fw fa-smile-o" aria-hidden="true"></i> Partner</a>
                    <ul>
                        <li><a href="javascript:void(0)" class="get-html" data-route="{{action('Lab\PartnerController@index')}}">Lista</a></li>
                        <li><a href="javascript:void(0)" class="get-html" data-route="{{action('Lab\PartnerController@create')}}">Crea nuovo elemento</a></li>
                    </ul>
                </li>

                <li class="dropdown"><a href=""><i class="fa fa-fw fa-picture-o" aria-hidden="true"></i> Photogallery</a>
                    <ul>
                        <li><a href="javascript:void(0)" class="get-html" data-route="{{action('Lab\PhotogalleryController@index')}}">Lista</a></li>
                        <li><a href="javascript:void(0)" class="get-html" data-route="{{action('Lab\PhotogalleryController@create')}}">Crea nuovo elemento</a></li>
                    </ul>
                </li>

                <li class="dropdown"><a href=""><i class="fa fa-fw fa-video-camera" aria-hidden="true"></i> Videogallery</a>
                    <ul>
                        <li><a href="javascript:void(0)" class="get-html" data-route="{{action('Lab\VideogalleryController@index')}}">Lista</a></li>
                        <li><a href="javascript:void(0)" class="get-html" data-route="{{action('Lab\VideogalleryController@create')}}">Crea nuovo elemento</a></li>
                    </ul>
                </li>                

                <li class="dropdown"><a href=""><i class="fa fa-fw fa-id-card-o" aria-hidden="true"></i> Portfolio</a>
                    <ul>
                        <li><a href="javascript:void(0)" class="get-html" data-route="{{action('Lab\PortfolioController@index')}}">Lista</a></li>
                        <li><a href="javascript:void(0)" class="get-html" data-route="{{action('Lab\PortfolioController@create')}}">Crea nuovo elemento</a></li>
                    </ul>
                </li>

                <li class="dropdown"><a href=""><i class="fa fa-fw fa-book" aria-hidden="true"></i> Catalogo</a>
                    <ul>
                        <li class="dropdown" style="background-color: #c1bfbf;"><a href="#"><b>Categorie</b></a>
                        <ul>
                            <li><a href="javascript:void(0)" class="get-html" data-route="{{action('Lab\CategoryController@index')}}">Lista</a></li>
                            <li><a href="javascript:void(0)" class="get-html" data-route="{{action('Lab\CategoryController@create')}}">Crea nuovo elemento</a></li>
                        </ul>
                        <li class="dropdown" style="background-color: #c1bfbf;"><a href="#"><b>Sotto categorie</b></a>
                        <ul>
                            <li><a href="javascript:void(0)" class="get-html" data-route="{{action('Lab\SubcategoryController@index')}}">Lista</a></li>
                            <li><a href="javascript:void(0)" class="get-html" data-route="{{action('Lab\SubcategoryController@create')}}">Crea nuovo elemento</a></li>
                        </ul>
                        <li class="dropdown" style="background-color: #c1bfbf;"><a href="#"><b>Prodotti</b></a>
                        <ul>
                            <li><a href="javascript:void(0)" class="get-html" data-route="{{action('Lab\ProductController@index')}}">Lista</a></li>
                            <li><a href="javascript:void(0)" class="get-html" data-route="{{action('Lab\ProductController@create')}}">Crea nuovo elemento</a></li>
                        </ul>
                     </li>
                    </ul>
                </li>                

                <li class="dropdown"><a href=""><i class="fa fa-fw fa-bullhorn" aria-hidden="true"></i> Banner</a>
                    <ul>
                        <li><a href="javascript:void(0)" class="get-html" data-route="{{action('Lab\BannerController@index')}}">Lista</a></li>
                        <li><a href="javascript:void(0)" class="get-html" data-route="{{action('Lab\BannerController@create')}}">Crea nuovo elemento</a></li>
                    </ul>
                </li>

                <li class="dropdown"><a href=""><i class="fa fa-fw fa-comments-o" aria-hidden="true"></i> Review</a>
                    <ul>
                        <li><a href="javascript:void(0)" class="get-html" data-route="{{action('Lab\ReviewController@index')}}">Lista</a></li>
                        <li><a href="javascript:void(0)" class="get-html" data-route="{{action('Lab\ReviewController@create')}}">Crea nuovo elemento</a></li>
                    </ul>
                </li>

                <li class="dropdown"><a href=""><i class="fa fa-fw fa-shopping-cart" aria-hidden="true"></i> Ecommerce</a>
                    <ul>
                        <li class="dropdown" style="background-color: #c1bfbf;"><a href="#"><b>Coupon</b></a>
                        <ul>
                            <li><a href="javascript:void(0)" class="get-html" data-route="{{action('Lab\CouponController@index')}}">Lista</a></li>
                            <li><a href="javascript:void(0)" class="get-html" data-route="{{action('Lab\CouponController@create')}}">Crea nuovo elemento</a></li>
                        </ul>
                        <li class="dropdown" style="background-color: #c1bfbf;"><a href="#"><b>Met. di consegna</b></a>
                        <ul>
                            <li><a href="javascript:void(0)" class="get-html" data-route="{{action('Lab\ShipmentController@index')}}">Lista</a></li>
                            <li><a href="javascript:void(0)" class="get-html" data-route="{{action('Lab\ShipmentController@create')}}">Crea nuovo elemento</a></li>
                        </ul>
                        <li class="dropdown" style="background-color: #c1bfbf;"><a href="#"><b>Met. di pagamento</b></a>
                        <ul>
                            <li><a href="javascript:void(0)" class="get-html" data-route="{{action('Lab\PaymentController@index')}}">Lista</a></li>
                            <li><a href="javascript:void(0)" class="get-html" data-route="{{action('Lab\PaymentController@create')}}">Crea nuovo elemento</a></li>
                        </ul>
                        <li style="background-color: #c1bfbf;">
                            <a href="javascript:void(0);" class="get-html" data-route="{{action('Lab\CartController@index')}}"><b>Ordini</b></a>
                        </li>
                    </ul>                    
                </li>

                <li class="dropdown"><a href=""><i class="fa fa-fw fa-user-circle-o" aria-hidden="true"></i> Utenti registrati</a>
                    <ul>
                        <li><a href="javascript:void(0)" class="get-html" data-route="{{action('Lab\BusinessController@index')}}">Lista</a></li>
                    </ul>
                </li>                 

                <li class="dropdown"><a href=""><i class="fa fa-fw fa-building" aria-hidden="true"></i> Sedi / Uffici</a>
                    <ul>
                        <li><a href="javascript:void(0)" class="get-html" data-route="{{action('Lab\OfficeController@index')}}">Lista</a></li>
                        <li><a href="javascript:void(0)" class="get-html" data-route="{{action('Lab\OfficeController@create')}}">Crea nuovo elemento</a></li>
                    </ul>
                </li>

                <li class="dropdown"><a href=""><i class="fa fa-fw fa-user-secret" aria-hidden="true"></i> GDPR</a>
                    <ul>
                        <li>
                            <a href="javascript:void(0);" class="get-html" data-route="{{action('Lab\ParameterController@edit', array('gdpr'))}}">Info generali</a>
                        </li>
                    </ul>
                </li>

                <li class="dropdown"><a href=""><i class="fa fa-fw fa-cog" aria-hidden="true"></i> Parametri</a>
                    <ul>
                        @foreach ($arrParameters as $el)                        
                        <li>
                            <a href="javascript:void(0);" class="get-html" data-route="{{action('Lab\ParameterController@edit', array($el->module2nd))}}">{{$el->module2nd}}
                            </a>
                        </li>
                        @endforeach
                    </ul>
                </li>
            </ul>
        </div><!--leftmenu-->
        
    </div><!-- leftpanel -->
    
    <div class="rightpanel" id="rpc-container">
        
        @yield('breadcrumbs')

        @yield('pageheader')
        
        <div class="maincontent">
            <div class="maincontentinner">

                @yield('maincontentinner')

            </div>
        </div>
        
    </div>
    
</div>

</body>
</html>
