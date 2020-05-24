<?php

namespace G3n1us\Editor\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Alert extends Model
{
    protected $dates = ['start_date', 'end_date', 'created_at', 'updated_at'];
    
    protected $fillable = ['start_date', 'end_date'];    
    
    public function message(){
	    return $this->belongsTo(Message::class);
    }
    
    public function getSubjectAttribute($subject){
	    if($subject) return $subject;
	    $message = $this->message;
		return $message ? $message->subject : null;
    }
    
    public function getBodyAttribute($body){
	    if($body) return $body;
	    $message = $this->message;
		return $message ? $message->body : null;
    }
    
	public function getStartTimeAttribute($value) {
		if(!$value) return $value;
	    return Carbon::parse($value)->format('F j Y h:i:s A');
	}    
    
	public function getEndTimeAttribute($value) {
		if(!$value) return $value;
	    return Carbon::parse($value)->format('F j Y h:i:s A');
	}    
    
    /**
     * Scope a query to only include active alerts.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive($query)
    {
		return $query->where('start_time', '<', Carbon::now())->where('end_time', '>', Carbon::now());	    
    }    
}
