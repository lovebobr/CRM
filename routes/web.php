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
Route::get('managers', \App\Livewire\ManagersTab::class)->name('managers');
Route::get('/partners', \App\Livewire\Partners::class)->name('partners');
Route::middleware(['auth', 'role:manager'])->group(function () {
    Route::get('/manager-dashboard', \App\Livewire\ManagerDashboard::class)->name('manager.dashboard');
});
Route::get('/role-by-user', \App\Livewire\RoleByUser::class)->name('role-by-user');
require __DIR__.'/auth.php';
