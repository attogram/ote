<?php

use App\Http\Controllers\LanguageController;
use App\Http\Controllers\LexiconController;
use App\Http\Controllers\TokenController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('lexicon.index');
});

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
