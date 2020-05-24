<?php
namespace App\Mail;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\User;

class UserRegistered extends Mailable
{
    use Queueable, SerializesModels;
    
    public $user;
    
    public $message;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(User $user)
    {
	    $user->load('person');
        $this->user = $user;
    }
    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('mail.user_registered');
    }
}
