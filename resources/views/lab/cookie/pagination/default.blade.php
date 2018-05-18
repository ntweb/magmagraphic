@if ($arrElements->lastPage() > 1)

<ul class="pagination pagination-sm">
	@for ($i = 1; $i <= $arrElements->lastPage(); $i++)
    <li class="@if($arrElements->currentPage() == $i) active @endif">
		<a class="get-html" href="javascript:void(0)" data-route="{{$arrElements->url($i)}}&type={{request('type',null)}}&key={{request('key',null)}}">{{$i}}</a>
    </li>
	@endfor
</ul>

@endif