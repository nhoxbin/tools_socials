<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

/*Route::group(['prefix' => 'admin', 'middleware' => 'admin'], function () {
    Route::resource('/user/', 'UserController');
    Route::get('/', 'AdminController@index');
});*/

Route::group(['prefix' => 'auth'], function() {
	Route::post('login', 'AuthController@login')->name('login');
    Route::post('register', 'AuthController@register');

    Route::group(['middleware' => 'auth:api'], function() {
        Route::get('refresh', 'AuthController@refresh');
        // this /user path is used to fetchData on client side (vue-auth)
        Route::get('user', 'AuthController@user');
        Route::post('logout', 'AuthController@logout');

        // Route::get('user/{id}', 'UserController@show')->middleware('isAdminOrSelf');
        Route::resource('users', 'UserController')
            ->parameters(['users' => 'id'])
            ->middleware('isAdmin'); // change users param (users/{user}) to users/{id}
    });
});

Route::group([
    'as' => 'facebook.',
    'prefix' => 'facebook',
    'namespace' => 'Facebook',
    'middleware' => 'auth:api'
], function() {

    Route::post('login', 'AccountController@login')
        ->middleware('checkRole') // memberCannotLoginFBTwice
        ->name('login');
    
    Route::get('account/update', 'AccountController@update')->name('account.update');
    Route::resource('account', 'AccountController', [
        'names' => 'account',
        'only' => ['index', 'show', 'destroy'],
        'parameters' => ['account' => 'p_uid']
    ]);

    Route::group([
        'prefix' => '{p_uid}',
        'middleware' => 'hasAccountFB',
        'where' => ['p_uid' => '[0-9]+']
    ], function() {
        // Route::get('/', 'TestController@test');
        /*Route::group(['as' => 'feed.', 'prefix' => 'feed'], function() {
            Route::post('reactions', 'FeedController@reactions')->name('reactions');
        });

        Route::group(['as' => 'friends.', 'prefix' => 'friends'], function () {
            Route::get('/{p_uid}', 'FriendsController@getList')->where('p_uid', '[0-9]+')->name('getList');
            Route::get('/{uid}/unfriend', 'FriendsController@unfriend')->where('uid', '[0-9]+')->name('unfriend');
        });

        Route::group(['as' => 'messenger.', 'prefix' => 'messenger'], function() {
            Route::match(['GET', 'POST'], '/inbox', 'MessengerController@inbox')->name('inbox');
            Route::get('/inbox-kount', 'MessengerController@kount')->name('inbox-kount');
        });

        Route::group(['as' => 'groups.', 'prefix' => 'groups'], function() {
            Route::get('/list', 'GroupsController@getList')->name('list');
            Route::post('/out', 'GroupsController@out')->name('out');
        });*/

        Route::post('get_url_file', 'FeedController@getURLFile')->name('feed.uploadFile');
        Route::post('feed/{object_id}/comment', 'FeedController@comment')
            ->name('feed.comment');

        // lấy bài viết trên newfeed với type là page hoặc user
        Route::get('home/{type}/posts/{limit}', 'HomeController@getPosts')
            ->where('limit', '[0-9]+')
            ->name('home.getPosts');

        // lấy bài viết trên tường nhà của 1 hoặc nhiều user
        Route::get('feed/{uids}/posts/{limit_posts}/{limit_comments}', 'FeedController@getPosts')
            ->where(['limit_posts' => '[0-9]+', 'limit_comments' => '[0-9]+'])
            ->name('feed.getPosts');
    });
});

// Route::get('test', 'TestController@test');

Route::any('/{any}', function() {
    return response('404 API Not found!', 404);
})->where('any', '.*');