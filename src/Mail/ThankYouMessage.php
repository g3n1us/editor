<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

use Illuminate\Http\Request;



class ThankYouMessage extends Mailable
{
    use Queueable, SerializesModels;

	
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($item, $recipient)
    {

        $this->item = $item;
        $this->recipient = $recipient;

    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build(){
	    $this->subject('Thank you from the ASFS PTA');
		$sku = array_get($this->item, 'sku');
        return $this->markdown("mail.purchases.$sku", ['item' => $this->item, 'recipient' => $this->recipient]);
    }

    public function __toString(){
	    $this->build();
	    return array_get($this->buildMarkdownView(), 'html')->toHtml();
    }
}
