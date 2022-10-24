<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProjectController;
use Illuminate\Support\Facades\Route;

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

Auth::routes();

Route::get('/projects', [ProjectController::class, 'index'])->name('projects.index')->middleware('auth');
Route::post('/projects', [ProjectController::class, 'store'])->middleware('auth');
Route::get('/projects/{project}', [ProjectController::class, 'show'])->middleware('auth');

Route::get('/home', [HomeController::class, 'index'])->name('home');
