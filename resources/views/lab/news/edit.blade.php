@extends('lab.news.default')

@section('content')

    <div class="tabbedwidget tab-primary">
        <ul>
            @foreach ($languages as $localeCode => $l)
            <li><a href="#tabs-{{$localeCode}}">{{strtoupper($localeCode)}} <i class="fa fa-globe" aria-hidden="true"></i> <b>{{trans('lab.descriptions')}}</b></a></li>
            @endforeach
    
            <li><a href="#tabs-images"><i class="fa fa-camera" aria-hidden="true"></i> {{trans('lab.images')}}</a></li>
            <li id="refresh-tabs-attachments" class="get-html" data-route="{{action('Lab\UploadController@index', array($el->id, $uploadfolder))}}" data-container="#tabs-attachments"><a href="#tabs-attachments"><i class="fa fa-paperclip" aria-hidden="true"></i> {{trans('lab.attachments')}}</a></li>
            <li><a href="#tabs-settings"><i class="fa fa-wrench" aria-hidden="true"></i> {{trans('lab.settings')}}</a></li>
        </ul>

        @foreach ($languages as $localeCode => $l)
        <div id="tabs-{{$localeCode}}">            
            @include('lab.news.forms.create')
        </div>
        @endforeach

        <div id="tabs-images">
            @include('lab.news.forms.images')
        </div>
        <div id="tabs-attachments">
            {{-- loaded in RPC --}}
        </div>        
        <div id="tabs-settings">
            @include('lab.news.forms.settings')
        </div>
    </div>

@endsection