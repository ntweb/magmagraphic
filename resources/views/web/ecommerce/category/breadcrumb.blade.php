{{-- important --}}
<?php
    $_c = (session()->has('breadcrumb_cat')) ? session()->get('breadcrumb_cat') : null;
    $_sc = (session()->has('breadcrumb_subcat')) ? session()->get('breadcrumb_subcat') : null;
    $_prod = (session()->has('breadcrumb_prod')) ? session()->get('breadcrumb_prod') : null;
?>

@if(!isset($show_search))

<section class="page-header">
	<div class="container">
		<ul class="breadcrumb">
			<li><a href="{{ action('Web\HomepageController@index') }}" title="Vai a: homepage">Home</a></li>
            @if ($_c))
                <li><a href="{{ cat_url($_c) }}" title="Vai alla pagina: {{ $_c->mtitle }}">{{ $_c->title }}</a></li>
            @endif
            @if ($_sc)
                <li><a href="{{ sub_url($_sc) }}" title="Vai alla pagina: {{ $_sc->mtitle }}">{{ $_sc->title }}</a></li>
            @endif
            @if ($_prod)
                <li><a href="{{ prod_url($_prod) }}" title="Vai alla pagina: {{ $_prod->mtitle }}">{{ $_prod->title }}</a></li>
            @endif
		</ul>
	</div>
</section>

@endif