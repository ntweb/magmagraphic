@extends('lab.cookie.default')

@section('content')

    <div class="tabbedwidget tab-primary">
        <ul>
            @foreach ($languages as $localeCode => $l)
            <li><a href="#tabs-{{$localeCode}}">{{strtoupper($localeCode)}} <i class="fa fa-globe" aria-hidden="true"></i> <b>{{trans('lab.descriptions')}}</b></a></li>
            @endforeach
            <li><a href="#tabs-settings"><i class="fa fa-wrench" aria-hidden="true"></i> {{trans('lab.settings')}}</a></li>
        </ul>

        @foreach ($languages as $localeCode => $l)
        <div id="tabs-{{$localeCode}}">            
            @include('lab.cookie.forms.create')
        </div>
        @endforeach

        <div id="tabs-settings">
            @include('lab.cookie.forms.settings')
        </div>
    </div>

@endsection