<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

use Illuminate\Http\Request;



class HelpMessage extends Mailable
{
    use Queueable, SerializesModels;

	
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Request $request)
    {

        $this->request = $request;

    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build(){
	    $this->subject('ASFS Help Request from ' . $this->request->name);
	    if($this->request->input('email'))
	        $this->replyTo($this->request->email);
        
        return $this->markdown('mail.help_message', ['request' => $this->request]);
    }

    public function __toString(){
	    $this->build();
	    return array_get($this->buildMarkdownView(), 'html')->toHtml();
    }
}
