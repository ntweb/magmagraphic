@extends('web.index')

@section('content')

{{-- important fields 'data' --}}
<div style="display: none;" data-widget="infowindow" data-lat="{{param('latitude')}}" data-lng="{{param('longitude')}}">{!! param('infowindow','extras') !!}</div>
<div style="width: 100%; height: 300px;" data-widget="map"></div>

    {{$page->title}}
    <br>
    {{$page->description}}


<p class="alert alert-success" data-widget="p_sent" style="display: none;">
    {{trans('labels.contact_sent')}}
</p>

<form class="ns ns-hide" data-route="{{action('Web\ContactController@send')}}" data-callback="$('*[data-widget=p_sent]').show(0);$('.ns-hide').hide(0)">
{{ csrf_field() }}
   
   {{trans('labels.name')}} <br>
   <input type="text" name="name"> <br>
   {{trans('labels.subject')}} <br>
   <input type="text" name="subject"> <br>
   {{trans('labels.email')}} <br>
   <input type="text" name="email"> <br>
   {{trans('labels.telephone')}} <br>
   <input type="text" name="telephone"> <br>
   {{trans('labels.message')}} <br>
   <textarea name="message" cols="30" rows="10"></textarea>

   <br><br>

    <div class="checkbox">
        <label>
            <input type="checkbox" value="1" name="check_contact">
            {!! trans('web.contact_check') !!}
        </label>
    </div>

    <div class="checkbox">
        <label>
            <input type="checkbox" value="1" name="check_privacy">
            {!! trans('web.privacy_check') !!}
        </label>
    </div>

   <br>
   <button type="submit">{{trans('labels.send')}}</button>

</form>

@endsection