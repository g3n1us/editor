<?php

namespace G3n1us\Editor;

use G3n1us\Editor\Models\Message;
use G3n1us\Editor\Models\Person;
use G3n1us\Editor\Models\User;
use G3n1us\Editor\Models\MessageRecipient;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Mail\Markdown;
use Illuminate\Support\Facades\Log;
use Illuminate\Routing\Controller as BaseController;


use App\Mail\DefaultMessage;
use Illuminate\Support\Facades\Mail;


class MessageController extends BaseController
{
	public function __construct(){
//         $this->middleware('auth')->except('index');		
/*
	    $this->middleware('can:compose-mail')->only('create', 'edit', 'update', 'store')->except('index');
	    $this->middleware('can:send-mail')->only('send', 'destroy')->except('index');
*/
	}
	
	
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
	    if($request->search)
	        $messages = Message::search($request->search)->paginate(50);
        else
		    $messages = Message::orderBy('updated_at', 'desc')->paginate(50);

        return view('g3n1us_editor::dashboard.messages', ['messages' => $messages]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('g3n1us_editor::dashboard.messages_edit', ['message' => new Message(['send_date' => Carbon::now()])]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
	    $m = new Message;
	    $m->subject = $request->input("message.subject");
	    $m->body = $request->input("message.body");
	    $m->send_date = new Carbon($request->input('message.send_date', 'now'));
	    $m->save();	
	    
	    return redirect(route('messages.edit', $m));    
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Message  $message
     * @return \Illuminate\Http\Response
     */
    public function show(Message $message)
    {
// 	    $markdown = new Markdown(view(), config('mail.markdown'));
	    
        return view('g3n1us_editor::dashboard.messages_show', ['message' => $message]);
    }
    
    
    public function raw(Message $message){
	    $user = auth()->user() ?? new User;
	    $current_person = $user->person;

	    return response(new DefaultMessage($message, $current_person));
    }

    /**
     * Send the email.
     *
     * @param  \App\Message  $message
     * @return \Illuminate\Http\Response
     */
    public function send(Request $request, Message $message)
    {
	    if(strlen(trim($message->body)) < 100)
		    return redirect()->back()->withError('The message body must be at least 100 characters. Be sure to save the message before attempting to send.');
		    
		    
		$message->ready = true;
		$message->save();
		
		if(config('app.env') == 'production')
		    $recipients = Person::emailable()->get();
	    else
		    $recipients = [auth()->user()->person];
	    foreach($recipients as $recipient){
			MessageRecipient::firstOrCreate(['message_id' => $message->id, 'person_id' => $recipient->id]);    
	    }
	    
	    return redirect('/dashboard/messages')->with('message', 'The email is in the queue to be sent');    
    }


    /**
     * Send test email.
     *
     * @param  \App\Message  $message
     * @return \Illuminate\Http\Response
     */
    public function send_test(Request $request, Message $message)
    {
	    $now = Carbon::now();
	    $addresses = explode(',', $request->test_addresses);
	    $addresses = array_map('trim', $addresses);
	    $people_recipients = Person::whereIn('email', $addresses)->get();
	    $actual_addresses = $people_recipients->pluck('email')->toArray();
	    
	    $addresses[] = 'tech@jmbdc.com';
	    $recipient_diff = array_diff($addresses, $actual_addresses);
	    
	    $user_feedback_message = 'The email has been sent. ';
	    if(!empty($recipient_diff)){
		    $user_feedback_message .= "However, the following addresses did not have a corresponding user on the site: <pre class='my-2'>" . implode(", ", $recipient_diff) . "</pre>Mail may only be sent to registered users.";
	    }
	    
	    
	    foreach($people_recipients as $person){
		    Mail::to($person->email)->send(new DefaultMessage($message, $person));
	    }
	        
	        
        Log::info('Mail sent to ' . $people_recipients->count() . ' addresses on ' . Carbon::now(), ['message' => $message->id]);
        $config = (array) $message->config;
        if(!isset($config['sends']))
	        $config['sends'] = [];
        $config['sends'][] = 'Mail sent to ' . $people_recipients->count() . ' addresses on ' . Carbon::now();
        $message->config = $config;
        $message->save();
	    return redirect(route('messages.edit', $message))->with('message', $user_feedback_message);    
        
	    
/*
		$message->ready = true;
		$message->save();
		
	    $recipients = Person::emailable()->get();
	    foreach($recipients as $recipient){
			MessageRecipient::firstOrCreate(['message_id' => $message->id, 'person_id' => $recipient->id]);    
	    }
	    
        Mail::to('sean@jmbdc.com')->send(new DefaultMessage($message));
	    
	    return redirect('/dashboard/messages')->with('message', 'The email is in the queue to be sent');    
*/
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Message  $message
     * @return \Illuminate\Http\Response
     */
    public function edit(Message $message)
    {
        return view('g3n1us_editor::dashboard.messages_edit', ['message' => $message]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Message  $message
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Message $message)
    {
		$message->subject = $request->message['subject']; 
		$message->reply_to = $request->input('message.reply_to'); 
		$message->ready = $request->input('message.ready', $message->ready); 
		$message->template = $request->input('message.template', 'general'); 
		$message->body = (string)$request->input('message.body', '<p></p>');
		    
		$message->send_date = new Carbon($request->message['send_date']); 
		$message->save();
		
		if($request->input('alert.start_time') && $request->input('alert.end_time')){
			$alert = $message->alert()->firstOrNew([]);
			$alert->start_time = new Carbon($request->input('alert.start_time'));
			$alert->end_time = new Carbon($request->input('alert.end_time'));
			$message->alert()->save($alert);
		}
		
        return redirect()->back()->with('message', 'Saved');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Message  $message
     * @return \Illuminate\Http\Response
     */
    public function destroy(Message $message)
    {
        $message->delete();
        return redirect(route('messages.index'));
    }
}
