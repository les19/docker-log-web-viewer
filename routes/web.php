<?php

use App\Http\Controllers\LoggerController;
use App\Http\Middleware\DefaultHeaderChecker;
use Illuminate\Support\Facades\Route;

Route::any('/logger', LoggerController::class)
    ->middleware([DefaultHeaderChecker::class])
    ->name('logger.handle');
