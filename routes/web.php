<?php

use App\Http\Controllers\TokenController;
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

Route::get('/', [TokenController::class, 'issue']);
Route::get('/asadmin', [TokenController::class, 'issueAsAdmin']);
Route::get('/asstudent', [TokenController::class, 'issueAsStudent']);
Route::get('/asmentor', [TokenController::class, 'issueAsMentor']);
Route::get('/validate', [TokenController::class, 'validateToken']);
