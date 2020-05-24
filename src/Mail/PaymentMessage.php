<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

use Illuminate\Http\Request;



class PaymentMessage extends Mailable
{
    use Queueable, SerializesModels;

	
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Request $request, $charge)
    {

        $this->request = $request;
        $this->charge = $charge;

    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build(){
	    $this->subject('Payment received for: ' . $this->request->name);

        return $this->markdown('mail.payment_notification', ['request' => $this->request, 'charge' => $this->charge]);
    }

    public function __toString(){
	    $this->build();
	    return array_get($this->buildMarkdownView(), 'html')->toHtml();
    }
}
