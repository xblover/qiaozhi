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

//Route::middleware('auth:api')->get('/user', function (Request $request) {
//    return $request->user();
//});

$api = app('Dingo\Api\Routing\Router');

$api->version('v1', [
    'namespace' => 'App\Http\Controllers\Api'
], function($api) {
    // 短信验证码
    $api->post('verificationCodes', 'VerificationCodesController@store')
        ->name('api.verificationCodes.store');

    // 用户注册
    $api->post('users', 'UsersController@store')
        ->name('api.users.store');

    // 用户登陆
    $api->post('login', 'UsersController@login')
        ->name('api.users.login');

    // 图片验证码
    $api->post('captchas', 'CaptchasController@store')
        ->name('api.captchas.store');

    $api->group([
        'middleware' => 'api.throttle',
        'limit' => config('api.rate_limits.upload_day.limit'),
        'expires' => config('api.rate_limits.upload_day.expires'),
    ], function($api) {

        $api->group(['middleware'=>"throttle:3,1"], function($api) {
            // 文件上传
            $api->post('upload', 'UsersController@upload');
        });
    });


});
