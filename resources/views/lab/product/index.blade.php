@extends('lab.product.default')

@section('content')

    <div class="headtitle">
        <div class="btn-group">
            <button data-toggle="dropdown" class="btn dropdown-toggle">@if (isset($type)) {{$type->category->title}} <i class="fa fa-angle-right" aria-hidden="true"></i> {{$type->title}} @else {{trans('lab.all')}} @endif <span class="caret"></span></button>
            <ul class="dropdown-menu">
                <li><a href="javascript:void(0);" class="get-html" data-route="{{action('Lab\ProductController@index')}}">{{trans('lab.all')}}</a></li>
                <li class="divider"></li>
                @foreach ($arrType as $t)
                <li><a href="javascript:void(0);" class="get-html" data-route="{{action('Lab\ProductController@index')}}?type={{$t->id}}">{{$t->title}}</a></li>
                @endforeach
            </ul>
          </div>
        <h4 class="widgettitle title-primary">{{trans('lab.list')}} | <i class="fa fa-facebook-official"></i> Facebook xml: <a href="{{ url('facebook/xml') }}" target="_blank" style="color: aqua;">{{ url('facebook/xml') }}</a></h4>
    </div>

    @if(count($arrElements))

    <table class="table table-striped responsive">
        <thead>
            <tr>
            @if(request('type') || count($arrType) == 1)
                <th></th>
            @endif
                <th class="center"><i class="fa fa-power-off" aria-hidden="true"></i></th>
                <th class="center"><i class="fa fa-home" aria-hidden="true"></i></th>
                <th class="center"><i class="fa fa-usd" aria-hidden="true"></i></th>
                <th class="center"><i class="fa fa-facebook-official" aria-hidden="true"></i></th>
                <th>ID</th>
                <th>{{trans('lab.title')}}</th>
                <th>{{trans('lab.category')}}</th>
                <th class="right">{{trans('lab.price')}}</th>
                <th>{{trans('lab.created_by')}}</th>
                <th>{{trans('lab.created_at')}}</th>
                <th>{{trans('lab.updated_at')}}</th>
                <th></th>
            </tr>
        </thead>
        <tbody class="sortable" data-route="{{action('Lab\OrderController@update', array($table))}}" data-token="{{ csrf_token() }}">
            @foreach ($arrElements as $el)
            <tr id="item-{{$el->id}}">
            @if(request('type') || count($arrType) == 1)
                <td class="handle"><i class="fa fa-sort" aria-hidden="true"></i></td>
            @endif
                <td class="center">
                    <a href="javascript:void(0);" class="change-flag {{$active = $el->active ? 'colorGreen' : 'colorRed'}}" data-route="{{action('Lab\ProductController@changeFlag', array($el->id, 'active'))}}">                        
                        <i class="fa fa-circle tip" aria-hidden="true" data-placement="top" data-original-title="{{trans('lab.active')}} ?"></i>
                    </a>
                </td>
                <td class="center">
                    <a href="javascript:void(0);" class="change-flag {{$homepage = $el->homepage ? 'colorGreen' : 'colorRed'}}" data-route="{{action('Lab\ProductController@changeFlag', array($el->id, 'homepage'))}}">                        
                        <i class="fa fa-circle tip" aria-hidden="true" data-placement="top" data-original-title="{{trans('lab.homepage')}} ?"></i>
                    </a>
                </td>
                <td class="center">
                    <a href="javascript:void(0);" class="change-flag {{$discount = $el->discount ? 'colorGreen' : 'colorRed'}}" data-route="{{action('Lab\ProductController@changeFlag', array($el->id, 'discount'))}}">                        
                        <i class="fa fa-circle tip" aria-hidden="true" data-placement="top" data-original-title="{{trans('lab.discount')}} ?"></i>
                    </a>
                </td>
                <td class="center">
                    @if($el->price > 0)
                        <a href="javascript:void(0);" class="change-flag {{$facebookxml = $el->facebookxml ? 'colorGreen' : 'colorRed'}}" data-route="{{action('Lab\ProductController@changeFlag', array($el->id, 'facebookxml'))}}">
                            <i class="fa fa-circle tip" aria-hidden="true" data-placement="top" data-original-title="Export in Facebook catalogue ?"></i>
                        </a>
                    @endif
                </td>
                <td>{{$el->id}}</td>
                <td>{{$el->title}}</td>
                <?php $subcategory = $el->subcategory; ?>
                <td>{{$subcategory->category->title}} <i class="fa fa-angle-right" aria-hidden="true"></i> {{$subcategory->title}}</td>
                <td class="right">{{$el->price}} <i class="fa fa-euro"></i></td>
                <td>{{$el->created_by->name}}</td>
                <td>{{date($default_lang['datetime'], strtotime($el->created_at))}}</td>
                <td>{{date($default_lang['datetime'], strtotime($el->updated_at))}}</td>
                <td class="right">
                    <div class="btn-group btn-group-xs">
                        <button href="javascript:void(0);" class="btn btn-primary get-html" data-route="{{action('Lab\ProductController@edit', array($el->id))}}"><i class="fa fa-fw fa-pencil" aria-hidden="true"></i></button>
                        <button href="javascript:void(0);" class="btn btn-danger delete-json" data-route="{{action('Lab\ProductController@destroy', array($el->id))}}" data-token="{{ csrf_token() }}" data-callback="$(btn).closest('tr').remove()" data-confirm="{{trans('lab.confirm-delete')}}"><i class="fa fa-fw fa-trash" aria-hidden="true"></i></button>
                    </div>                                
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <!-- pagination -->
    @include('lab.product.pagination.default')

    @else

    <div class="alert alert-block">
        <h4>Ops!</h4>
        <p>{{trans('lab.no_element_found')}}</p>
    </div>

    @endif

@endsection