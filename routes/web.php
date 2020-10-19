<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Livewire\ShowThread;
use App\Http\Livewire\IndexThreads;
use App\Http\Livewire\ShowUser;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth:sanctum', 'verified'])->get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');

Route::get('/forum', IndexThreads::class)->name('forum');
Route::get('/forum/{channel}', IndexThreads::class);

Route::get('/forum/{channel}/{thread}', ShowThread::class);

Route::get('/user/{user}', ShowUser::class)->name('user')->middleware('auth');