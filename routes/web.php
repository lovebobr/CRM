<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LeadController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::view('/', 'welcome');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

Route::get('hello/{name}',function($name){
    return 'Hello '.$name;
});

Route::get('users', \App\Livewire\Users::class)->name('users');
//Route::get('/',[LeadController::class,'getIndex'])->name('index');
Route::get('/createLead',\App\Livewire\Leads::class)->name('createLead');
require __DIR__.'/auth.php';
