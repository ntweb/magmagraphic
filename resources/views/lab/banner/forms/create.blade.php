<form class="stdform stdform2 ns" 
    data-route="{{$route}}" 
        @if(isset($el)) 
            data-method='PUT' 
        @else 
            data-callback="getHtml(param)" 
        @endif
>

{!! csrf_field() !!}    {{-- token --}}
@if (isset($l))         {{-- locale --}}
<input type="hidden" name="lang" value="{{$localeCode}}">
@endif

    @if(!isset($el))
        
        {{-- creation --}}
        <p>
            <label>{{trans('labels.title')}}</label>
            <span class="field">
                <input type="text" name="title" class="form-control" />
            </span>
        </p>

    @else

        {{-- editing --}}
        <p>
            <label>{{trans('labels.title')}}</label>
            <span class="field">
                <input type="text" name="title" class="form-control" value="{{@$el->translate($localeCode)->title}}" />
            </span>
        </p>

        <p>
            <label>{{trans('labels.abstract')}}</label>
            <span class="field">
                <textarea name="abstract" class="form-control" rows="5" maxlength="250" >{{@$el->translate($localeCode)->abstract}}</textarea>
            </span>
        </p>        

        <p>
            <label>{{trans('labels.url')}}</label>
            <span class="field">
                <textarea name="url" class="form-control" rows="5" maxlength="250" >{{@$el->translate($localeCode)->url}}</textarea>
            </span>
        </p>        

        <p>
            <label>{{trans('labels.description')}}</label>
            <span class="field">
                <textarea name="description" class="wysiwyg_editor">{{@$el->translate($localeCode)->description}}</textarea>
            </span>
        </p>

    @endif
                                    
    <p class="stdformbutton">
        <button type="submit" class="btn btn-primary">{{trans('labels.save')}}</button>
    </p>
</form>