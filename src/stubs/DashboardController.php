<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Traits\ControllerRouter;
use App\Person;
use App\Message;
use App\User;
use App\Family;
use App\Teacher;
use App\Purchase;
use App\Services\MemberImporter;
use Illuminate\Support\Facades\Gate;
use Storage;
use Carbon\Carbon;
use Zipper;
use Auth;
use DB;


use Jrean\UserVerification\Traits\VerifiesUsers;
use Jrean\UserVerification\Facades\UserVerification;


use App\Mail\HelpMessage;
use Illuminate\Support\Facades\Mail;



class DashboardController extends Controller
{
    use ControllerRouter, VerifiesUsers;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
	    $this->middleware('isVerified')->except(['getHelp', 'postHelp']);

// 	    $this->middleware('can:upload-csv')->only('getUploadCsv', 'postUploadCsv');
    }


    public function getIndex(){
	    session()->flash('returnTo', 'dashboard');
		$current_user = auth()->user();
        return view('dashboard.index');
    }

	public function getUser(Request $request, \App\User $user){
		return 'DashboardController@getUser';
	}

	public function getUserPerson(Request $request, \App\User $user){
	    if(Gate::denies('edit-all-people'))
		    abort(403);

		if($request->html){
			$fields = array_only($user->person->toArray(), [
				'street1',
				'city',
				'state',
				'zip',
			]);
			return '<p>' . implode('<br>', $fields) . '</p>';
		}
		else
			return $user->person;
	}

	public function getFilemanager(Request $request){
		return view('filemanager');
	}

	public function postTrash(Request $request){
		if(!$request->has('delete_path')){
			throw new \Exception("parameter delete_path cannot be empty");
			exit;
		}
		Storage::disk('trash')->put($request->delete_path, '');
		cache()->forget('cached_files');
		return ['success' => Storage::disk('trash')->has($request->delete_path)];
	}


	public function getFamily(Request $request, Family $family){
		return ["members" => $family->members];
	}

	public function getTeacherStudents(Request $request, Teacher $teacher){
		return ["members" => $teacher->students];
	}

	public function getUsers(Request $request){
		if($request->user_ids){
			$users = User::whereIn('id', explode(',', $request->user_ids))->orderBy('name')->paginate(100);
		}
		else
			$users = User::orderBy('name')->paginate(100);
		$abilities = array_keys(array_except(Gate::abilities(), ['edit-person', 'admin-site']));
		return view('dashboard.edit_users', ['users' => $users, 'abilities' => $abilities]);
	}

	public function postUsers(Request $request, User $user){
	    if(Gate::denies('edit-users'))
		    abort(403);

	    $this->validate($request, [
	        'name' => 'bail|required|max:172',
	        'email' => 'bail|required|email',
	        'verified' => 'required|boolean',
	        'person_id' => 'nullable|integer',
	        //'permissions' => 'required',
	    ]);

		$user->name = $request->input('name', $user->name);
		$user->email = $request->input('email', $user->email);
		$user->verified = $request->input('verified', $user->verified);
		$user->approved = $request->input('verified', $user->approved);
		$user->permissions = $request->input('permissions', $user->permissions);
		$user->person_id = $request->input('person_id', $user->person_id);
		if($user->save())
			return redirect()->back()->with('message', 'The user has been updated');
		else
			return redirect()->back()->with('error', 'An error occurred. The user has NOT been updated');
	}

	public function putUsers(Request $request){
	    if(Gate::denies('edit-users'))
		    abort(403);

	    $this->validate($request, [
	        'name' => 'bail|required|max:172',
	        'email' => 'bail|required|email|unique:users',
	        'verified' => 'required|boolean',
	    ]);
		$user = new User;
		$user->name = $request->input('name');
		$user->email = $request->input('email');
		$user->verified = $request->input('verified');
		$user->approved = $request->input('approved');
		$user->person_id = $request->input('person_id');
		$user->password = str_random(100);
// 		if($user->save())
		if(false)
			return redirect()->back()->with('message', 'The user has been created');
		else
			return redirect()->back()->with('error', 'An error occurred. The user has NOT been created');
	}


	public function postUploadRoster(Request $request){
	    if(Gate::denies('upload-csv'))
		    abort(403);
/*
		$csv = $request->file('file');

		if(!$csv)
			return redirect()->back()->with('error', 'The file is missing! Please try again.');
		if (!$csv->isValid())
			return redirect()->back()->with('error', 'There seems to be a problem with the file that was uploaded. Try again and/or contact support.');

		if($csv->getClientMimeType() != 'text/csv' || strtolower($csv->getClientOriginalExtension()) != 'csv')
			return redirect()->back()->with('error', "The file doesn't seem to be a .csv file. Try again using this file type.");

		$importer = new MemberImporter(file_get_contents($csv->path()));
		if($importer())
			return redirect()->back()->with('message', 'The database has been updated! Thank you!!');
		else
			return redirect()->back()->with('error', 'An error occurred');
*/

	}


	public function getHelp(){
		$nullUser = new User;
		$nullUser->person = new Person;
		$data['user'] = auth()->user() ?: $nullUser;
		return view('dashboard.get_help', $data);
	}

	public function postHelp(Request $request){

	    $this->validate($request, [
	        'name' => 'bail|required|max:255',
	        'email' => 'email|required_without:phone',
	        'preferred-contact' => 'required',
	        'description' => 'required',
	        'g-recaptcha-response' => 'required',
	        'phone' => 'required_without:email',
	    ]);

		$curl = curl_init();
		curl_setopt_array($curl, [
		    CURLOPT_RETURNTRANSFER => 1,
		    CURLOPT_URL => 'https://www.google.com/recaptcha/api/siteverify',
		    CURLOPT_USERAGENT => 'Laravel Recaptcha Verification',
		    CURLOPT_POST => 1,
		    CURLOPT_POSTFIELDS => [
			    'secret'   => env('RECAPTCHA_SECRET_KEY'),
		        'response' => $request->input('g-recaptcha-response'),
		        'remoteip' => $request->server->get('REMOTE_ADDR'),
		    ]
		]);
		$resp = curl_exec($curl);
		curl_close($curl);
		$resp = json_decode($resp);
		if(!$resp->success)
			return redirect()->back()->with('error', 'Captcha error, please try again.')->withInput();

		$emails = explode(',', config('app.help_desk_users'));
		$users = User::whereIn('email', $emails)->get();

        Mail::to($users)->send(new HelpMessage($request));
	    return redirect()->back()->with('message', 'Your message has been received. Someone will be in touch shortly.');

	}

	public function getDirectory(Request $request){
	    if($request->search)
	        $people = Person::search($request->search)->paginate(50);
        else
		    $people = Person::orderBy('last_name')->paginate(50);

        return view('dashboard.directory', ['people' => $people]);
	}


	public function getPurchases(){
	    if(Gate::denies('get-accounting'))
		    abort(403);

	    $context = [];

	    $context['skus'] = existing_skus();

/*
	    $context['lookup'] = Person::select('id', 'email', 'config->email as email2')->get()->map(function($p){
		    return [
			    str_replace('"', '', $p->email) => $p->id,
			    str_replace('"', '', $p->email2) => $p->id
		    ];
	    })->collapse()->filter(function($v,$k){
		    return !!trim($k);
	    });
*/

	    $context['lookup'] = cache()->remember('purchases_typeahead', 7 * 24 * 60, function(){
		    return Person::select('id', 'email', 'config->email as email2')->get()->map(function($p){
			    return [
				    str_replace('"', '', $p->email) => $p->id,
				    str_replace('"', '', $p->email2) => $p->id
			    ];
		    })->collapse()->filter(function($v,$k){
			    return !!trim($k);
		    });
	    });

		$all_purchases = $this->listPurchases();

		$context['purchases'] = $this->listPurchases();

		return view('dashboard.purchases', $context);
	}

	public function putPurchases(Request $request){
	    if(Gate::denies('get-accounting'))
		    abort(403);

		$purchase = new Purchase;
		$allrequest = $request->all();
		$allrequest['items'][0]['amount'] = (int) $allrequest['items'][0]['amount'] * 100;
		$purchase->request = $allrequest;
		$purchase->charge = $request->type;
	    if($request->person_id)
		    $purchase->person_id = $request->person_id;
	    if($purchase->save())
		    return redirect()->back()->with('message', 'Saved');

	}


	public function getPerson(Request $request, Person $person){
	    if(Gate::denies('edit-all-people'))
		    abort(403);
		$info = $person->show();
		$info['zip'] = $person->zip;
		$info['state'] = $person->state;
		return $info;
	}


// Allows an super-admin to login as any other user. Undocumented.
// Route is /dashboard/login-as-user/{user_id}
	public function getLoginAsUser(Request $request, User $user = null){
	    if(Gate::denies('admin-site')){
		    abort(403);
		    die();
	    }
	    if(!$user) abort(404);

		Auth::login($user);
		return redirect('/dashboard');
	}


	public function getCacheClear(){
	    if(Gate::denies('admin-site'))
		    abort(403);

	    cache()->flush();
	    return redirect()->back()->with('message', "cache cleared");
	}

	private function listPurchases(){
		$cached_purchases = cache()->remember('purchases_list', 60 * 24 * 7, function(){
			$purchases = Purchase::orderBy('request->item')->with('person')->get()->map(function($purchase){
				$items = collect($purchase->request['items']);
				$items = $items->map(function($i) use($purchase){
					if(!$purchase->person->id){
						$name_parts = explode(' ', array_get($purchase->request, 'card.name'));
						$purchase->person->first_name = $name_parts[0];
						$purchase->person->last_name = array_get($name_parts, 1);
						$purchase->person->email = array_get($purchase->request, 'email');
					}
					$row = array_merge($i, $purchase->person->getPublishedInfo());
					$row['city'] = $purchase->person->city;
					$row['state'] = $purchase->person->state;
					$row['zip'] = $purchase->person->zip;
					$row['is_teacher'] = $purchase->person->is_teacher ? "yes" : "no";
					$row['amount'] = $row['amount'] / 100;
					$row['created_at'] = $purchase->created_at;
					return $row;
				});

				return $items->all();
			});

			$purchases = $purchases->flatten(1)->sortByDesc('created_at');
			$purchases = $purchases->groupBy('sku');
			return $purchases;
		});

		if(!request()->has('allpurchases')){
			$cached_purchases = $cached_purchases->filter(function($p){
				$days = 6 * 30; //months * days
				$newest_one = $p->first();
				return $newest_one && $newest_one['created_at'] > new Carbon("$days days ago");
			});
		}

		return $cached_purchases;

	}

	public function getPurchaseReport(){
		die('');
		// This has been removed in favor of JS solution


	    if(Gate::denies('get-accounting'))
		    abort(403);

		$purchases = $this->listPurchases();

		if (!is_dir(storage_path('tmp'))) {
		  // dir doesn't exist, make it
		  mkdir(storage_path('tmp'));
		}

		$zipname = storage_path('tmp') . '/' . Carbon::now() . ".zip";
		$zipper = Zipper::make($zipname);
		foreach($purchases as $sku => $items){
			$zipper->addString("$sku.csv", to_csv($items));
		}
		$zipper->close();

		return response()->download($zipname)->deleteFileAfterSend(true);
	}



	public function getLogs(){
	    if(Gate::denies('admin-site')){
		    abort(403);
		    die();
	    }
		$zipname = storage_path('tmp') . '/logs-' . Carbon::now() . ".zip";
		$zipper = Zipper::make($zipname);
		$zipper->add(storage_path('logs'));
	    $zipper->close();
	    return response()->download($zipname)->deleteFileAfterSend(true);
	}



	public function getPrintDirectory(){
	    if(Gate::denies('edit-all-people')){
		    abort(403);
		    die();
	    }
		$data = [];

		$data['by_family'] = cache()->store('file')->remember('print_dir_by_family_v2', 10000, function(){
			$families = Family::orderBy('surname')->with('members')->get();
			$families = $families->filter(function($v){
				return $v->members->count() > 0;
			});

			return $families->chunk(12);
		});

		return view('dashboard.print_directory', $data);
	}



	public function getResetDirectory(Request $request){
	    if(Gate::denies('upload-csv'))
		    abort(403);

	    if(config('app.env') !== 'local'){
	    	abort(500); die('');
	    }

		\DB::unprepared(file_get_contents(storage_path('reset.sql')));
		Person::get()->each(function($person){
			$user = User::where('email', $person->email)->first();
			if($user){
				$person->user_id = $user->id;
				$person->save();
				$user->person_id = $person->id;
				$user->save();
			}
			else{
				$person->user_id = null;
				$person->save();
			}
		});
		return redirect()->back()->with(['message' => 'RESET DONE']);
	}


/*
	private function compare_or_update_people($request_people, $perform_update = false){
		return $request_people->map(function($v) use($perform_update){

			$current_person = Person::where('hash', $v['hash'])->get();
			if($current_person->isEmpty())
				return null;

			if($current_person->count() > 1){
//				dd($current_person);
				$fam = Family::where('hash', $v['family_hash'])->first();
				$current_person = $fam->members()->where('hash', $v['hash'])->get();
				dd($current_person);
			}

			else
				$current_person = $current_person->first();


			// If people have the same name, search within the family to determine individuality
			// ... to do!!!

			$v['home_phone'] = sanitize_phone($v['home_phone']);
			$v['cell_phone'] = sanitize_phone($v['cell_phone']);

			$properties_to_update = array_except($v, ['hash', 'family_hash', 'family_hash_extended']);

			$before = $current_person->toArray();
			$changed_fields = [];
			foreach($properties_to_update as $field => $val){
				if($current_person[$field] != $val){
					$changed_fields[] = $field;
				}
			}
			foreach($properties_to_update as $field => $val){
				$current_person->{$field} = $val;
			};

			// ONLY update if the argument is set. This is used for the initial uplaod that only checks status to report back to the user,
			// and also to actually perform the updates.
			if($perform_update){
				$current_person->save();

				$family = Family::where('hash', $v['family_hash'])->first();
				if(!$family) {
					$family = new Family(['hash' => $v['family_hash']]);
					$family->surname = $v['last_name'];
					$family->{"meta->extended_hash"} = $v['family_hash_extended'];
					$family->save();
				}
				$family->members()->save($current_person);

			}

			$current_person->before = $before;
			$current_person->changes = $changed_fields;

			return $current_person;
		});
	}
*/


/*
	private function directoryStatusOrUpdate(Request $request, $perform_update = false){
	    if(Gate::denies('upload-csv'))
		    abort(403);

		$request_people = collect($request->users);

$request_people = $request_people->groupBy('hash')->flatMap(function ($items) {
    $quantity = $items->count();
    if($quantity > 1) dd($items);
    return $items->map(function ($item) use ($quantity) {

        $item['quantity'] = $quantity;

        return $item;

    });

});
dd($request_people);
		$hashes = $request_people->pluck('hash');

		$person_lookup = $this->compare_or_update_people($request_people, $perform_update);

		$existing_hashes = Person::pluck('hash')->all();
		$users_to_delete = array_diff(array_filter($existing_hashes), $hashes->toArray());
		$users_to_delete = Person::whereIn('hash', $users_to_delete)->get();
		$users_to_delete->transform(function($u){
			$u->status = 'DELETE';
			$u->family_hash = $u->family->hash;
			$u->family_hash_extended = 'set_to_delete_hack_'.rand().rand().rand().rand().rand();
			return $u;
		});

		return ['current' => $person_lookup, 'delete' => $users_to_delete];
	}

	public function postDirectoryStatus(Request $request){
		return $this->directoryStatusOrUpdate($request, false);
	}



	public function postDirectoryUpdate(Request $request){
		return $this->directoryStatusOrUpdate($request, true);
	}
*/



/*
	public function postUpdateFromSpreadsheet(Request $request){
	    if(Gate::denies('upload-csv'))
		    abort(403);

		$ok = true;
		$last_name =array_pluck($request->all(), 'last_name');
		$last_name = array_unique($last_name);
		$last_name = implode(', ', $last_name);

		foreach($request->all() as $individual){
			if(strtolower($individual['status']) == 'new'){
				// add user to the database
				$person = Person::firstOrNew(['hash' => $individual['hash']]);
				$person->first_name = array_get($individual, 'first_name');
				$person->last_name = array_get($individual, 'last_name');
				$person->home_phone = array_get($individual, 'home_phone');
				$person->email = array_get($individual, 'email');
				$person->grade = array_get($individual, 'grade');

				$person->cell_phone = array_get($individual, 'cell_phone');
				$person->street1 = array_get($individual, 'street1');
				$person->city = array_get($individual, 'city');
				$person->state = array_get($individual, 'state');
				$person->zip = array_get($individual, 'zip');
				$person->hash = array_get($individual, 'hash');
				$person->save();

				$family_hash = array_get($individual, 'family_hash_extended');
				$person_family = Family::firstOrNew(['meta->extended_hash' => $family_hash]);
				$person_family->meta = ['extended_hash' => $family_hash];
				$person_family->hash = $individual['family_hash'];
				$person_family->surname = $last_name;
				$person_family->save();
				$person->family()->associate($person_family);
				$person->save();
				$ok = true;

			}
			else if(strtolower($individual['status']) == 'delete'){
				$person = Person::where('hash', $individual['hash'])->first();
				$person->delete();
				$ok = true;
			}

		}
		return ["response" => "success"];
	}
*/

	public function getUploadRoster(){
	    if(Gate::denies('upload-csv'))
		    abort(403);
	    $teachers = Teacher::with('person')->get();
	    $teachers = $teachers->map(function($t){
		    $t = $t->toArray();
		    return array_merge(
		    	array_except($t, 'person'),
		    	array_only($t['person'], ['first_name', 'last_name', 'grade', 'email'])
	    	);
	    });

		return view('dashboard.upload_csv', [
			'current_people' => Person::with('family')->get(),
			'current_teachers' => $teachers,
			'current_users' => User::get()->map(function($u){
				return [
					'id' => $u->id,
					'person_id' => $u->person_id,
					'email' => strtolower($u->email),
				];
			})->keyBy('email'),
		]);
	}


	public function postUpdateAuto($data){
	    if(Gate::denies('upload-csv'))
		    abort(403);

		foreach($data['families_to_add'] as $family_hash => $family_to_add){
// 			if(!count($family_to_add)) continue;
// 			if(!isset($family_to_add[0]['family_hash_extended'])) dd($data['families_to_add']);;
			$surname = array_pluck($family_to_add, 'last_name');
			$surname = array_unique($surname);
			sort($surname);
			$surname = implode(', ', $surname);
			$new_fam = Family::firstOrNew(['hash' => $family_hash]);
			// $new_fam->{'meta->extended_hash'} = $family_to_add[0]['family_hash_extended'];
			$new_fam->surname = $surname;
			$new_fam->save();
			foreach($family_to_add as $new_member){
				$props = array_except($new_member, ['family_hash_extended', 'family_hash', '_teacher']);
				if(strtolower(array_get($props, 'grade', '')) === 'k') $props['grade'] = '0';
				$member = $new_fam->members()->where('first_name', $props['first_name'])->where('grade', array_get($props, 'grade'))->first();
				if(!$member){
					$member = new Person($props);
					$new_fam->members()->save($member);
				}
				else{
					$filtered_props = array_only($props, array_keys($member->getAttributes()));
					foreach($filtered_props as $k => $v){
						if($k == 'id') continue;
						else
							$member->{$k} = $v;
					}
					$member->save();

				}
				if(isset($new_member['_teacher'])){
					$member->teachers()->sync([$new_member['_teacher']]);
/*
					if($existing = DB::table('person_teacher')->where('person_id', $member->id)->first()){
						DB::table('person_teacher')
				            ->where('id', $existing->id)
				            ->update(['teacher_id' => $new_member['_teacher']]);
					}
					else{
						DB::table('person_teacher')->insert(
						    ['teacher_id' => $new_member['_teacher'], 'person_id' => $member->id]
						);
					}
*/
				}
				if(!empty($new_member['user_id'])){
					$u = User::find($new_member['user_id']);

					$u->person_id = $member->id;
					$u->save();
				}

			}
		}
		return ['message' => 'Update complete!'];
	}

	public function postUpdateHomepage(Request $request){
		$hp = new \App\Homepage;
		$hp->callouts = $request->input('callouts', []);
		$hp->feed = $request->input('feed', []);
		$hp->save();

		return $hp;
	}

/*
public function getTest(){
	$p = Person::find('3543');
	$p->teachers()->sync([]);

// 	$p = $p->toArray();
	ddd(array_keys($p->getAttributes()));
}
*/

/*
	public function getCalendar(Request $request){
		return view('dashboard.calendar');
	}
*/



}
