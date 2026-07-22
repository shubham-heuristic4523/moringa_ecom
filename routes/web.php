<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Mail;

Route::get('/', function () {
    return view('hello');
});
Route::get('/test-mail', function () {

    Mail::raw('Laravel Email Test', function ($message) {
        $message->to('yourgmail@gmail.com')
                ->subject('Test Mail');
    });

    return 'Mail Sent';
});