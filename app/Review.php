<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    protected $table = 'lab_reviews';

    public function el(){
    	if ($this->type == 'news')
			return $this->belongsTo('\App\News','id_el');

        if ($this->type == 'products')
            return $this->belongsTo('\App\Product','id_el');

    	if ($this->type == 'pages')
			return $this->belongsTo('\App\Page','id_el');
    }

    public function created_by(){
		return $this->belongsTo('\App\User','id_created_by');
    }

    public function updated_by(){
		return $this->belongsTo('\App\User','id_updated_by');
    }

    public function scopeActive ($query, $active = '1') {
        if ($active)
            return $query->where('active','=',$active);

        return $query;
    }       
}
