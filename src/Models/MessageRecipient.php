<?php

namespace G3n1us\Editor\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;


class MessageRecipient extends Model
{
    protected $dates = ['sent_at', 'created_at', 'updated_at'];
    
    protected $guarded = [];
    
    protected static function boot()
    {
        parent::boot();

	    static::addGlobalScope('sent', function (Builder $builder) {
	        $builder->whereNull('sent_at');
	    });
    }
    
    
    public function message(){
	    return $this->belongsTo(Message::class);
    }
    public function person(){
	    return $this->belongsTo(Person::class);
    }
    
    
}
