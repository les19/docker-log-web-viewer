<?php

use App\Http\Controllers\LoggerController;
use App\Http\Middleware\DefaultHeaderChecker;
use Illuminate\Support\Facades\Route;

Route::any(config('app.dispatcher-endpoint'), LoggerController::class)
    ->middleware([DefaultHeaderChecker::class])
    ->name('dispatcher.handle');
