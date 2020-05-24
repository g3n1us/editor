<?php
use Illuminate\Support\Facades\Artisan;

/*
|--------------------------------------------------------------------------
| Console Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of your Closure based console
| commands. Each Closure is bound to a command instance allowing a
| simple approach to interacting with each command's IO methods.
|
*/

Artisan::command('g3n1us:editor', function () {
    $this->info('*******');
    $this->comment('G3n1us Editor Interactive Assistant');
    $this->info('*******');
    $this->line('');
    
    $assistant = new EditorAssistant($this);
    $options = [
	    'publish' => 'Publish Template: this adds a new default layout using editor',
    ];
    $option = $this->choice('What would you like to do?', $options);
    
    $assistant->{$option}();
    
})->describe('Interactive assistance with Editor');

class EditorAssistant{
	
	public $command;
	
	public function __construct($command){
		$this->command = $command;
	}
	
	public function publish(){
		copy(__DIR__."/views/editor_layout.blade.php", resource_path("views/layouts/editor_layout.blade.php"));
		$this->command->info("Done!");
		$this->command->comment("Saved to: ".resource_path("views/layouts/editor_layout.blade.php"));
	}
	
}


Artisan::command('send_emails', function(){
	$eligible_emails = Message::ReadyToSend()->get();
	foreach($eligible_emails as $eligible_email){
		foreach($eligible_email->recipients as $recipient){
			if($recipient->person && $recipient->person->wants_email){
				Mail::to($recipient->person)->send(new DefaultMessage($eligible_email, $recipient->person));
			    $this->comment('Emailing: ' . $recipient->person->email);
			}
			$recipient->sent_at = Carbon::now();
			$recipient->save();
		}
	    $this->comment('Database updated');
	}
})->describe('Send a batch of emails');
