<?php

	// get Page by id or module
	function page($id_or_module, $active = null) {
		return \App\Page::active($active)->where('id', '=', $id_or_module)->orWhere('module', '=', $id_or_module)->first();
	}

	function page_url($id_or_module, $active = null) {
		if (!isset(Session::get($id_or_module)[App::getLocale()])) {
			$page = page($id_or_module, $active = null);
			if (!$page)
				return "#";

			Session::put($id_or_module, array(App::getLocale() => action('Web\PageController@show', array($page->id, $page->murl))));
		}

		return Session::get($id_or_module)[App::getLocale()];
	}

	function news_url($el) {
		// chencge route if needed
		return action('Web\NewsController@show', array($el->type, $el->murl, $el->id));
	}	

	// get date in locale format
	function fdate($date) {
		if (!$date)
			return null;

		$locale = App::getLocale();
		$config = config('laravellocalization.supportedLocales.'.$locale);

		// $l = \App\Language::where('lang', '=', $locale)->first();
		// return date($l->date, strtotime($date));

		return date($config['date'], strtotime($date));
	}

	// get datetime in locale format
	function fdatetime($date) {
		if (!$date)
			return null;

		$locale = App::getLocale();
		$config = config('laravellocalization.supportedLocales.'.$locale);

		// $l = \App\Language::where('lang', '=', $locale)->first();
		// return date($l->date, strtotime($date));
		return date($config['datetime'], strtotime($date));
	}

	// get euro format
	function euro ($number, $dec = 2 ) {
		return number_format ($number, $dec,",",".");
	}

	// get percent format
	function percent ($number, $dec = 2 ) {
		return number_format ($number, $dec,",",".");
	}

	// get parameter
	function param($label, $field = 'value', $sep = null) {
		if (!Session::has($label)) {
			$p = \App\Parameter::where('label', '=', $label)->first();
			if ($p) {

				$v = $p->$field;
				if ($sep)
					$v = explode($sep, $p->$field);

				Session::put($label, $v);
				return Session::get($label);
			}
			return null;			
		}
		return Session::get($label);
	}


	function types($label) {
		$types = \App\Parameter::where('label', '=', $label)->first();
		return explode(',', $types->value);
	}

	// get youtube video pic
	// size => hq, mq, sd, max or ''
	function youtube_img($url, $size = 'mq') {

		$queryString = parse_url($url, PHP_URL_QUERY);
		parse_str($queryString, $params);

		return "https://img.youtube.com/vi/".$params['v']."/".$size."default.jpg";
	}

	// get youtube video iframe
	function youtube_video($url) {
		$url = 'https://www.youtube.com/oembed?url='.$url.'&format=json';
		$data = json_decode(file_get_contents($url));

		return $data->html;
	}

	// get social share
	function share($el) {

		//'delicious','digg','email','evernote','facebook','gmail','gplus','linkedin','pinterest','reddit','scoopit','telegramMe','tumblr','twitter','viadeo','vk',
		$url = Request::url();
		$description = isset($el->mdescription) ? $el->mdescription : '';
		return Share::load($url, $description)->services('facebook', 'gplus', 'twitter');
	}	

	// get doc of model
	function doc($el, $field) {
		$id = $el->id;
		$gallery = '';
		if ($field == 'filename') { // in gallery upload
			$id = $el->id_el;
			$gallery = 'mm/';
		} 

		$folder = $el->uploadfolder;
		$filename = $folder.'/'.$id.'/'.$gallery.$el->$field;

		return url('media/'.$folder.'/'.$id.'/'.$gallery.$el->$field);
	}
	
	// get img of model
	function img($el, $field, $rid = null, $quality = 60) {

		$id = $el->id;
		$gallery = '';
		if ($field == 'filename') { // in gallery upload
			$id = $el->id_el;
			$gallery = 'mm/';
		} 

		$folder = $el->uploadfolder;

		if (isset($el->password)) { // è l'avatar di un utente
			$id = '';
			$folder = 'avatar';
		}

		$filename = $folder.'/'.$id.'/'.$gallery.$el->$field;

		$rid = strtolower($rid);

		// ridimensiono
		if ($rid && Storage::disk('docs')->exists($filename)) { 
			// controllo se esiste gia il file ridimensionato
			if (!Storage::disk('docs')->exists($folder.'/'.$id.'/'.$gallery.$rid.'-'.$el->$field)) {
				$img = Image::make('media/'.$filename);
				$xxx = explode('x', $rid);
		    	$w = $xxx[0];
		    	$h = $xxx[1];
		    	if ($w == 'n' || $h == 'n'){ // ridimensiono
					$img->resize($w, $h, function ($constraint) {
					    $constraint->aspectRatio();
					});		  				
		    	}
		    	else { // crop
		    		$img->fit($w, $h);
		    	}
		    	$img->save('media/'.$folder.'/'.$id.'/'.$gallery.$rid.'-'.$el->$field, $quality);
			}
		}

		if ($rid) $rid = str_replace('-', '', $rid).'-';
		return url('media/'.$folder.'/'.$id.'/'.$gallery.$rid.$el->$field);
	}

	// iva
	function ivato($prezzo, $iva) {
		$prezzo =  $prezzo + iva($prezzo, $iva);
		return sprintf("%01.2f", round($prezzo,2));
	}	

	function iva($prezzo, $iva) {		
		$prezzo =  $prezzo /100 * $iva;
		return sprintf("%01.2f", round($prezzo,2));
	}	

	// cart 
	function get_cart_total ($instance = 'main') {
		$_subtotal = 0;
		foreach (Cart::instance($instance)->content() as $rowId => $row) {
			$_subtotal += $row->options->product['price']*$row->qty;
		}

		return $_subtotal;
	}

	function get_cart_total_ivato ($instance = 'main') {
		$_subtotal = 0;
		foreach (Cart::instance($instance)->content() as $rowId => $row) {
			$_subtotal += ivato($row->options->product['price']*$row->qty, $row->options->product['tax']);
		}

		return $_subtotal;
	}
