<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\VideoDestroyController;
use App\Http\Controllers\VideoFileStoreController;
use App\Http\Controllers\VideoStoreController;
use App\Http\Controllers\VideoUpdateController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

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
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::post('/videos', VideoStoreController::class)->name('videos.store');
    Route::patch('/videos/{video}', VideoUpdateController::class)->name('videos.update');
    Route::post('/videos/{video}/file', VideoFileStoreController::class)->name('videos.file.store');
    Route::delete('/videos/{video}', VideoDestroyController::class)->name('videos.destroy');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
