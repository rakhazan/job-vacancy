<?php

use App\Http\Controllers\CandidateDetailController;
use App\Http\Controllers\JobTypeController;
use App\Http\Controllers\JobVacancyController;
use App\Http\Controllers\PurposeJobController;
use App\Http\Controllers\PurposeLetterController;
use App\Http\Controllers\UserController;
use App\Models\CandidateDetail;
use App\Models\JobType;
use App\Models\PurposeLetter;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;

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

Route::get('/login', [UserController::class, 'index'])->name('login');
Route::get('/register', [UserController::class, 'create'])->name('register');
Route::group([
    'as' => 'auth.',
    'prefix' => 'auth'
], function () {
    Route::post('/login', [UserController::class, 'authenticate'])->name('login');
    Route::post('/register', [UserController::class, 'store'])->name('register');
    Route::get('/logout', [UserController::class, 'logout'])->name('logout');
});


Route::middleware('auth')->group(function() {

    Route::get('/home', function () {
        Session::put('is_active', 'home');
        return view('home');
    })->name('home');

    Route::get('/aboutus', function () {
        Session::put('is_active', 'aboutus');
        return view('aboutus');
    })->name('aboutus');

    Route::get('/news-event', function () {
        Session::put('is_active', 'news');
        return view('news');
    })->name('news');

    Route::get('/how-to-apply', function () {
        Session::put('is_active', 'hta');
        return view('hta');
    })->name('hta');

    Route::get('/announcement', function () {
        Session::put('is_active', 'announcement');
        return view('announcement');
    })->name('announcement');

    Route::get('/contact', function () {
        Session::put('is_active', 'contact');
        return view('contact');
    })->name('contact');

    Route::get('/edit-profile', [CandidateDetailController::class, 'index'])->name('edit-profile');
    Route::post('/save-profile', [CandidateDetailController::class, 'saveChange'])->name('saveProfile');
    Route::group([
        'as' => 'job-vacancy.',
        'prefix' => 'job-vacancy'
    ], function () {
        Route::get('/', [JobTypeController::class, 'index'])->name('index');
        Route::get('/list', [JobVacancyController::class, 'index'])->name('joblist');
        Route::get('/data', [PurposeLetterController::class, 'index'])->name('data');
        Route::post('/save-letter', [PurposeLetterController::class, 'store'])->name('save');
        // Route::get('/apply', [PurposeJobController::class, 'store'])->name('apply');
    });

    Route::group([
        'as' => 'admin.',
        'prefix' => 'admin'], function () {
        Route::get('/register', function () {
            return view('admin.register');
        })->name('register');
    });
});
