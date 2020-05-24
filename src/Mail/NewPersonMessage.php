<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

use Illuminate\Http\Request;

use App\Person;



class NewPersonMessage extends Mailable
{
    use Queueable, SerializesModels;

	
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Person $person)
    {

        $this->person = $person;

    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build(){
	    $this->subject('Welcome to the ASFS PTA Website & Directory');

        return $this->markdown('mail.new_person', ['person' => $this->person]);
    }

    public function __toString(){
	    $this->build();
	    return array_get($this->buildMarkdownView(), 'html')->toHtml();
    }
}
