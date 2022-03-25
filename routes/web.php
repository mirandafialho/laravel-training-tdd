<?php

use App\Http\Controllers\RegisterController;
use App\Http\Controllers\Todo\CreateController;
use App\Http\Controllers\Todo\IndexController;
use App\Http\Controllers\Todo\UpdateController;
use App\Mail\Invitation;
use App\Models\Invite;

use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::post('invite', function () {
    Mail::to(request()->email)->send(new Invitation());

    Invite::create([
        'email' => request()->email
    ]);
});

Route::post('register', RegisterController::class)->name('register');

Route::get('todo', IndexController::class)->name('todo.index');
Route::post('todo', CreateController::class)->name('todo.store');
Route::put('todo', UpdateController::class)->name('todo.update');

