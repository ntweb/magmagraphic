@extends('lab.cookie.default')

@section('content')

    <div class="headtitle">
        <h4 class="widgettitle title-primary">{{trans('lab.list')}}</h4>
    </div>

    @if(count($arrElements))

    <table class="table table-striped responsive">
        <thead>
            <tr>
                <th class="center"><i class="fa fa-power-off" aria-hidden="true"></i></th>
                <th>ID</th>
                <th>{{trans('lab.title')}}</th>
                <th>{{trans('lab.created_at')}}</th>
                <th>{{trans('lab.updated_at')}}</th>
                <th></th>
            </tr>
        </thead>
        <tbody class="sortable" data-route="{{action('Lab\OrderController@update', array($table))}}" data-token="{{ csrf_token() }}">
            @foreach ($arrElements as $el)
            <tr id="item-{{$el->id}}">
                <td class="center">
                    <a href="javascript:void(0);" class="change-flag {{$active = $el->active ? 'colorGreen' : 'colorRed'}}" data-route="{{action('Lab\CookieController@changeFlag', array($el->id, 'active'))}}">
                        <i class="fa fa-circle tip" aria-hidden="true" data-placement="top" data-original-title="{{trans('lab.active')}} ?"></i>
                    </a>
                </td>
                <td>{{$el->id}}</td>
                <td>{{$el->title}}</td>
                <td>{{date($default_lang['datetime'], strtotime($el->created_at))}}</td>
                <td>{{date($default_lang['datetime'], strtotime($el->updated_at))}}</td>
                <td class="right">
                    <div class="btn-group btn-group-xs">
                        <button href="javascript:void(0);" class="btn btn-primary get-html" data-route="{{action('Lab\CookieController@edit', array($el->id))}}"><i class="fa fa-fw fa-pencil" aria-hidden="true"></i></button>
                        <button href="javascript:void(0);" class="btn btn-danger delete-json" data-route="{{action('Lab\CookieController@destroy', array($el->id))}}" data-token="{{ csrf_token() }}" data-callback="$(btn).closest('tr').remove()" data-confirm="{{trans('lab.confirm-delete')}}"><i class="fa fa-fw fa-trash" aria-hidden="true"></i></button>
                    </div>                                
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <!-- pagination -->
    @include('lab.cookie.pagination.default')

    @else

    <div class="alert alert-block">
        <h4>Ops!</h4>
        <p>{{trans('lab.no_element_found')}}</p>
    </div>

    @endif

@endsection