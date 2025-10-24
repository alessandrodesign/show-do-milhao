<?php

use App\Http\Controllers\Admin\QuestionController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('app');
});

Route::middleware('admin.guard')->prefix('admin')->group(function () {
    Route::resource('questions', QuestionController::class);
});
