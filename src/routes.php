<?php
  
Route::namespace('\\G3n1us\\Editor')->group(function(){

	Route::group(['middleware' => ['web']], function () {
	  
		Route::get('/editor_dashboard/filemanager', 'EditorController@getFilemanager');
		Route::post('/editor_dashboard/upload', 'EditorController@storeUpload');
		Route::delete('/editor_dashboard/trash', 'EditorController@deleteFile');
		
		// Route::get(str_finish(config('g3n1us_editor.page_root'), '/').'{path?}', '\\G3n1us\\Editor\\EditorController@routePage')->where('path', '(.*?)');
	
	});
	


	Route::prefix('/dashboard')->middleware(['web', 'auth'])->group(function () {
			
		Route::post('/messages/{message}/send', 'MessageController@send')->name('messages.send');
		Route::post('/messages/{message}/send_test', 'MessageController@send_test')->name('messages.send_test');
		
		Route::get('/pages', 'PageController@index')->name('pages.list');
		Route::put('/pages', 'PageController@create')->name('pages.create');
		Route::post('/pages', 'PageController@create_save')->name('pages.create_save');
		Route::get('/pages/{page}', 'PageController@edit')->name('pages.edit');
		Route::post('/pages/{page}', 'PageController@save')->name('pages.save');
		Route::delete('/pages/{page}', 'PageController@destroy')->name('pages.destroy');
		Route::get('/save_page_order', 'PageController@save_page_order')->name('save_page_order');
	
	});
	
});  
  

