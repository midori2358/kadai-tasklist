<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TasksController;
use App\Http\Controllers\UsersController; // 追記



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
    return view('dashboard');
});

Route::get('/dashboard', [TasksController::class,'index'])->middleware(['auth'])->name('dashboard');;
Route::resource('tasks',TasksController::class);

require __DIR__.'/auth.php';

     Route::group(['middleware' => ['auth']], function () {
    Route::resource('tasks', TasksController::class,);
});
