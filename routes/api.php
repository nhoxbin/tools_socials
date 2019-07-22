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

    Route::get('account', 'AccountController@show')->name('account.show');
    Route::post('/account/update', 'AccountController@update')->name('account.update');
    // Route::resource('account', 'AccountController', ['only' => ['show', 'update']]);

    Route::group([
        'prefix' => '{p_uid}',
        'middleware' => 'hasAccountFB',
        'where' => ['p_uid' => '[0-9]+']
    ], function() {
        /*Route::group(['as' => 'feed.', 'prefix' => 'feed'], function() {
            Route::post('reactions', 'FeedController@reactions')->name('reactions');
        });

        Route::group(['as' => 'friends.', 'prefix' => 'friends'], function () {
            Route::get('/{p_uid}', 'FriendsController@getList')->where('p_uid', '[0-9]+')->name('getList');
            Route::get('/{uid}/unfriend', 'FriendsController@unfriend')->where('uid', '[0-9]+')->name('unfriend');
        });

        Route::group(['as' => 'messenger.', 'prefix' => 'messenger'], function() {
            Route::get('/inbox-kount', 'MessengerController@kount')->name('inbox-kount');
        });

        Route::group(['as' => 'groups.', 'prefix' => 'groups'], function() {
            Route::get('/list', 'GroupsController@getList')->name('list');
            Route::post('/out', 'GroupsController@out')->name('out');
        });*/

        /*Route::group(['as' => 'get.'], function() {
            

            // Route::get('comment/{type}', 'CommentController@show')->name('getPostsHasCommented');
            Route::post('/{posts_id}/comment', 'CommentController@create')->name('comment');

            // xóa comment
            Route::post('/comment/{type}/{comment_id}/delete', 'CommentController@delete')
                ->where('comment_id', '[0-9]+')
                ->name('delete.comment');
        });*/

        Route::resource('comment', 'CommentController', [
            'names' => 'comment',
            'except' => ['edit', 'update', 'create', 'destroy'],
            'parameters' => ['comment' => 'type']
        ]);
        Route::delete('comment/{type}/delete/{commented_id}', 'CommentController@delete')->name('comment.delete');

        // lấy bài viết trên tường nhà của 1 hoặc nhiều user
        Route::get('feed/{uids}/posts/{limit}', 'FeedController@getPosts')
            ->where('limit', '[0-9]+')
            ->name('feed.getPosts');

        /*Route::group(['as' => 'auto.', 'prefix' => 'auto'], function() {
            // inbox
            Route::match(['GET', 'POST'], '/inbox', 'MessengerController@inbox')->name('inbox');
            // comment
            Route::post('/comment-home', 'HomeController@startComment')->name('comment-home');
        });*/
    });
});

Route::get('test', 'TestController@test');

Route::any('/{any}', function() {
    return response('404 Not found!', 404);
})->where('any', '.*');