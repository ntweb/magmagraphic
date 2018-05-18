<ul class="breadcrumbs">
    <li><a href="#"><i class="iconfa-home"></i></a> <span class="separator"></span></li>
    <li>{{$mod_name}}</li>
</ul>

<div class="pageheader">

    @if (isset($route_search))
    <form class="searchbar form-get-html" data-route="{{$route_search}}">
        <input type="text" name="key" placeholder="{{trans('lab.search_tips')}}" />
    </form>
    @endif

    @if (isset($back))
    <div class="pull-right">        
        <button class="btn btn-default get-html" data-route="{{$back}}"><i class="fa fa-angle-left" aria-hidden="true"></i> {{trans('lab.back')}}
        </button>
    </div>
    @endif

    <div class="pageicon"><span class="fa fa-pencil"></span></div>
    <div class="pagetitle">
        <h5>{{$mod_action}}</h5>
        <h1>{{$mod_object}}</h1>
    </div>
</div>

<div class="maincontent">
    <div class="maincontentinner">

        @yield('content')

    </div>
</div>