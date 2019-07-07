<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('artisan/migrate/{password_migrate}', function($password_migrate) {
	if ('fuckyou159@aA' == base64_decode($password_migrate)) {
	    $exitCode = Artisan::call('migrate', [
		    '--force' => true,
		]);
		return $exitCode;
	} else {
		return 'You do not have permission.... haha';
	}
});

Route::any('{any?}', function() {
	return view('welcome');
})->where('any', '.*');