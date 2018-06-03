<?php
  
Route::group(['middleware' => ['web']], function () {
  
	Route::get('/editor_dashboard/filemanager', '\\G3n1us\\Editor\\EditorController@getFilemanager');
	Route::post('/editor_dashboard/upload', '\\G3n1us\\Editor\\EditorController@storeUpload');
	Route::delete('/editor_dashboard/trash', '\\G3n1us\\Editor\\EditorController@deleteFile');
	
// 	Route::get(str_finish(config('g3n1us_editor.page_root'), '/').'{path}', '\\G3n1us\\Editor\\EditorController@routePage')->where('path', '(.*?)');

});
