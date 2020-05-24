<?php

namespace G3n1us\Editor\Models;

use Laravel\Scout\Searchable;

use Illuminate\Database\Eloquent\Model;

use App\Traits\hasPublishedProperties;

use Carbon\Carbon;

class Message extends Model
{
    use Searchable, hasPublishedProperties;
        
    protected $directory_fields = [
	    'subject',
	    'template',	    
	    'from',
	    'updated_at',
	    'sent',
    ];    
    
    protected $dates = ['sent_at', 'created_at', 'updated_at'];
    
    protected $appends = ['sent', 'from', 'url'];
    
    protected $fillable = ['send_date'];
    
	protected $casts = [
        'config' => 'array',
    ];    
    
    public function recipients(){
	    return $this->hasMany(MessageRecipient::class);
    }

	public function getSendDateAttribute($value) {
		if(!$value) return $value;
	    return Carbon::parse($value)->format('F j Y h:i:s A');
	}    
    
    public function getUrlAttribute(){
	    return '/dashboard/messages/raw/' . $this->id;
    }
    
	public function getSentAttribute($value) {
	    return $this->send_date;
	}    
    
	public function getFromAttribute($value) {
		return $this->reply_to;
	}    
    
    public function alert(){
	    return $this->hasOne(Alert::class)->withDefault();
    }
    
    public function hasAlert(){
	    return $this->alert->start_time !== null;
    }

    public function hasActiveAlert(){
	    return $this->alert()->active()->first();
    }
    
    public function getTemplateAttribute($val){
	    return is_null($val) ? 'general' : $val;
    }
    
    /**
     * Scope a query to only include active alerts.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeReadyToSend($query)
    {
		return $query->where('ready', true)->where('send_date', '<', Carbon::now());
    }     
}
