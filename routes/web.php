<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\LexiconController;
use App\Http\Controllers\TokenController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;

// Authentication Routes
Route::get('register', [RegisterController::class, 'create'])->name('register');
Route::post('register', [RegisterController::class, 'store']);
Route::get('login', [LoginController::class, 'create'])->name('login');
Route::post('login', [LoginController::class, 'store']);
Route::post('logout', [LoginController::class, 'destroy'])->name('logout');

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/validate', [HomeController::class, 'validate'])->name('validate');

Route::resources([
    'tokens' => TokenController::class,
    'languages' => LanguageController::class,
]);

Route::get('/lexicon', [LexiconController::class, 'index'])->name('lexicon.index');
Route::get('/lexicon/create', [LexiconController::class, 'create'])->name('lexicon.create');
Route::post('/lexicon', [LexiconController::class, 'store'])->name('lexicon.store');
Route::get('/lexicon/{entry}', [LexiconController::class, 'show'])->name('lexicon.show');
Route::get('/lexicon/{entry}/edit', [LexiconController::class, 'edit'])->name('lexicon.edit');
Route::put('/lexicon/{entry}', [LexiconController::class, 'update'])->name('lexicon.update');
Route::delete('/lexicon/{entry}', [LexiconController::class, 'destroy'])->name('lexicon.destroy');

Route::get('/lexicon/{entry}/add-attribute', [LexiconController::class, 'createAttribute'])->name('lexicon.create-attribute');
Route::post('/lexicon/{entry}/store-attribute', [LexiconController::class, 'storeAttribute'])->name('lexicon.store-attribute');
Route::get('/lexicon/{entry}/edit-attribute/{attribute}', [LexiconController::class, 'editAttribute'])->name('lexicon.edit-attribute');
Route::put('/lexicon/{entry}/update-attribute/{attribute}', [LexiconController::class, 'updateAttribute'])->name('lexicon.update-attribute');
Route::delete('/lexicon/{entry}/delete-attribute/{attribute}', [LexiconController::class, 'destroyAttribute'])->name('lexicon.destroy-attribute');

Route::get('/lexicon/{entry}/add-link', [LexiconController::class, 'createLink'])->name('lexicon.create-link');
Route::post('/lexicon/{entry}/store-link', [LexiconController::class, 'storeLink'])->name('lexicon.store-link');
Route::get('/lexicon/{entry}/edit-link/{link}', [LexiconController::class, 'editLink'])->name('lexicon.edit-link');
Route::put('/lexicon/{entry}/update-link/{link}', [LexiconController::class, 'updateLink'])->name('lexicon.update-link');
Route::delete('/lexicon/{entry}/delete-link/{link}', [LexiconController::class, 'destroyLink'])->name('lexicon.destroy-link');

Route::get('/import', [LexiconController::class, 'import'])->name('lexicon.import');
Route::post('/import', [LexiconController::class, 'handleImport'])->name('lexicon.handle-import');
Route::get('/export', [LexiconController::class, 'export'])->name('lexicon.export');

// Suggestion Routes
Route::get('/suggestions', [\App\Http\Controllers\SuggestionController::class, 'index'])->name('suggestions.index');
Route::get('/suggestions/create', [\App\Http\Controllers\SuggestionController::class, 'create'])->name('suggestions.create');
Route::post('/suggestions', [\App\Http\Controllers\SuggestionController::class, 'store'])->name('suggestions.store');
Route::get('/suggestions/{suggestion}', [\App\Http\Controllers\SuggestionController::class, 'show'])->name('suggestions.show');
Route::post('/suggestions/{suggestion}/approve', [\App\Http\Controllers\SuggestionController::class, 'approve'])->name('suggestions.approve');
Route::post('/suggestions/{suggestion}/reject', [\App\Http\Controllers\SuggestionController::class, 'reject'])->name('suggestions.reject');

// Admin Routes
Route::group(['prefix' => 'admin', 'as' => 'admin.', 'middleware' => ['auth', 'role:admin']], function () {
    Route::resource('users', \App\Http\Controllers\Admin\UserController::class);
});
