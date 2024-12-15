<?php

use App\Http\Controllers\PageController;
use App\Http\Controllers\ContactController;

// RouteDefinitions

Route::get('/', [PageController::class, 'home'])->name('home');
Route::post('/contact', [ContactController::class, 'submit'])->name('contact');
Route::get('/{page:slug}', [PageController::class, 'show'])->name('page.show');
