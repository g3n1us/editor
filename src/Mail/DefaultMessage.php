<?php

namespace G3n1us\Editor\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

use G3n1us\Editor\Models\Message;
use G3n1us\Editor\Models\Person;

class DefaultMessage extends Mailable
{
    use Queueable, SerializesModels;

	public $message;
	
	public $is_preview;
	
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Message $message, Person $person, $is_preview = null)
    {
        $this->message = $message;
        $this->person = $person;
		$this->is_preview = $is_preview;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build(){
	    $this->message->body = preg_replace('/(<img.*?src=")\/storage(.*?)(")/', "$1".url('storage')."$2$3", $this->message->body);
	    $this->subject($this->message->subject);
	    if(!empty($this->message->reply_to))
            $this->replyTo($this->message->reply_to);
        $templatename = $this->message->template ?: 'default';
        config(['mail.markdown.theme' => $templatename]);

        return $this->markdown('mail.defaultmessage', ['template' => $templatename, 'is_preview' => $this->is_preview, 'person' => $this->person]);
    }

    public function __toString(){
	    $this->build();
	    return array_get($this->buildMarkdownView(), 'html')->toHtml();
    }
}
