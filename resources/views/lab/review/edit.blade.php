@extends('lab.review.default')

@section('content')

    <div class="tabbedwidget tab-primary">
        <ul>
            <li><a href="#tabs-it">{{trans('lab.review')}}</b></a></li>    
            <li><a href="#tabs-settings"><i class="fa fa-wrench" aria-hidden="true"></i> {{trans('lab.settings')}}</a></li>
        </ul>

        <div id="tabs-it">            
            @include('lab.review.forms.create')
        </div>
        <div id="tabs-settings">
            @include('lab.review.forms.settings')
        </div>
    </div>

@endsection