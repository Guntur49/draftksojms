<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\WorkspaceController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return auth()->check() ? redirect()->route('workspace.index') : redirect()->route('login');
});

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'create'])->name('login');
    Route::post('/login', [AuthController::class, 'store'])->name('login.store');
});

Route::middleware('auth')->group(function () {
    Route::get('/workspace', [WorkspaceController::class, 'index'])->name('workspace.index');
    Route::post('/hospitals', [WorkspaceController::class, 'storeHospital'])->name('hospitals.store');
    Route::delete('/hospitals/{hospital}', [WorkspaceController::class, 'destroyHospital'])->name('hospitals.destroy');
    Route::post('/hospitals/{hospital}/checklist-items', [WorkspaceController::class, 'storeChecklistItem'])->name('checklist-items.store');
    Route::put('/checklist-items/{checklistItem}', [WorkspaceController::class, 'updateChecklistItem'])->name('checklist-items.update');
    Route::delete('/checklist-items/{checklistItem}', [WorkspaceController::class, 'destroyChecklistItem'])->name('checklist-items.destroy');
    Route::post('/administration-letters', [WorkspaceController::class, 'storeAdministrationLetter'])->name('administration.store');
    Route::get('/administration-letters/{administrationLetter}/pdf', [WorkspaceController::class, 'downloadAdministrationLetterPdf'])->name('administration.pdf');
    Route::get('/administration-letters/{administrationLetter}/view', [WorkspaceController::class, 'viewAdministrationLetterPdf'])->name('administration.view');
    Route::put('/administration-letters/{administrationLetter}', [WorkspaceController::class, 'updateAdministrationLetter'])->name('administration.update');
    Route::delete('/administration-letters/{administrationLetter}', [WorkspaceController::class, 'destroyAdministrationLetter'])->name('administration.destroy');

    Route::post('/internal-letters', [WorkspaceController::class, 'storeInternalLetter'])->name('internal.store');
    Route::get('/internal-letters/{internalLetter}/pdf', [WorkspaceController::class, 'downloadInternalLetterPdf'])->name('internal.pdf');
    Route::get('/internal-letters/{internalLetter}/view', [WorkspaceController::class, 'viewInternalLetterPdf'])->name('internal.view');
    Route::put('/internal-letters/{internalLetter}', [WorkspaceController::class, 'updateInternalLetter'])->name('internal.update');
    Route::delete('/internal-letters/{internalLetter}', [WorkspaceController::class, 'destroyInternalLetter'])->name('internal.destroy');

    Route::post('/logout', [AuthController::class, 'destroy'])->name('logout');
});
