<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Cookie extends Model
{
	use \Dimsav\Translatable\Translatable;
    
    protected $table = 'lab_cookies';
    public $translatedAttributes = ['title', 'description'];

    public function created_by(){
		return $this->belongsTo('\App\User','id_created_by');
    }

    public function updated_by(){
		return $this->belongsTo('\App\User','id_updated_by');
    }      

    public function scopeActive ($query, $active = null) {
    	if ($active)
        	return $query->where('active','=',$active);

        return $query;
    }

}
