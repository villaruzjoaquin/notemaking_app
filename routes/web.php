<?php

use App\Http\Controllers\NoteController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::resource('/notes', NoteController::class)->middleware(['auth']); // The middleware auth prevents users from seeing this page if they are not logged in.

// The code Below is the equivalent of the code above simply by using a resource controller

// Route::get('/notes', );

// Route::get('/notes/{note}', ); // The {note} is the unique ID in the url to retrieve the proper Note when clicked.

// Route::get('/notes/create', ); // Display the form to create the note

// Route::get('/notes', );

// // Edit
// // Update
// // Destroy

require __DIR__.'/auth.php';
