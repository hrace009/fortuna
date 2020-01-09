<?php

use Illuminate\Support\Facades\Route;

Route::get('oauth/{driver}', 'Auth\OAuthController@redirectToProvider')->name('oauth')->middleware('socialite');
Route::get('oauth/{driver}/callback', 'Auth\OAuthController@handleProviderCallback')->name('oauth.callback')->middleware('socialite');
Route::post('payments/notifications', 'PaymentNotifications@notification')->name('payments.notifications');

Route::get('/{vue?}', 'FrontController@index')->where('vue', '^(?!.*api).*$[\/\w\.-]*');
