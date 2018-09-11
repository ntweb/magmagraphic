@if (!Auth::check())
	@if (!Session::has('cookie_accepted'))
	<form action="{{ action('Web\PrivacyController@accept') }}" method="post">
		{!! csrf_field() !!}
		<input type="hidden" name="redirect" value="{{ Request::fullUrl() }}">

		<div id="cookie-info">

			{!! trans('web.cookie') !!}

			<div style="margin-top: 10px; margin-bottom: 10px; width: 100%; text-align: center">
				<table class="table table-bordered table-condensed" style="background: #fff; width: 80%; max-width: 800px; margin: auto;">
					<tr>
						@foreach($arrCookies as $c)
						<td style="color: black">
							<input type="checkbox" name="cookie[]" value="{{ $c->id }}" @if($c->checked) checked @endif @if($c->disabled) disabled @endif>
							<a href="javascript:void(0);" class="cookie-info" data-v="#cookie-{{ $c->id }}" style="color: black">{{ $c->title }}</a>
						</td>
						@endforeach
						<td class="text-right" style="color: black">
							<button type="submit" class="btn btn-xs btn-success"><i class="fa fa-check"></i> {{trans('labels.accetto')}}</button>
						</td>
					</tr>
					@foreach($arrCookies as $c)
					<tr style="display: none" id="cookie-{{ $c->id }}" class="cookie-description">
						<td style="font-size: 12px; color: black; text-align: left;" colspan="{{ count($arrCookies) + 1 }}" >
							<b>{{ $c->title }}</b>:
							{!! $c->description !!}
						</td>
					</tr>
					@endforeach
				</table>
			</div>

		</div>

	</form>
	@endif
@endif