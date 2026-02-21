<?php

use App\Http\Controllers\DocumentController;
use App\Http\Controllers\FolderController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

// Page d'accueil → redirige vers documents
Route::get('/', function () {
    return redirect()->route('documents.index');
});

// Routes auth générées par Breeze
require __DIR__.'/auth.php';

// ═══════════════════════════════
// ESPACE UTILISATEUR
// ═══════════════════════════════
Route::middleware(['auth'])->group(function () {

    // Documents
    Route::get('/documents',                           [DocumentController::class, 'index'])->name('documents.index');
    Route::post('/documents/upload',                   [DocumentController::class, 'upload'])->name('documents.upload');
    Route::get('/documents/shared',                    [DocumentController::class, 'sharedWithMe'])->name('documents.shared');
    Route::get('/documents/recent',                    [DocumentController::class, 'recent'])->name('documents.recent');
    Route::get('/documents/favorites',                 [DocumentController::class, 'favorites'])->name('documents.favorites');
    Route::get('/documents/search',                    [DocumentController::class, 'search'])->name('documents.search');
    Route::get('/documents/{document}/download',       [DocumentController::class, 'download'])->name('documents.download');
    Route::get('/documents/{document}/preview',        [DocumentController::class, 'preview'])->name('documents.preview');
    Route::patch('/documents/{document}/status',       [DocumentController::class, 'toggleStatus'])->name('documents.toggle-status');
    Route::patch('/documents/{document}/favorite',     [DocumentController::class, 'toggleFavorite'])->name('documents.favorite');
    Route::delete('/documents/{document}',             [DocumentController::class, 'destroy'])->name('documents.destroy');

    // Corbeille
    Route::get('/trash',                               [DocumentController::class, 'trash'])->name('documents.trash');
    Route::delete('/trash/empty',                      [DocumentController::class, 'emptyTrash'])->name('documents.empty-trash');
    Route::patch('/trash/{document}/restore',          [DocumentController::class, 'restore'])->name('documents.restore');
    Route::delete('/trash/{document}/force',           [DocumentController::class, 'forceDelete'])->name('documents.force-delete');

    // Paramètres
    Route::get('/settings',                            [ProfileController::class, 'settings'])->name('settings');
    Route::post('/settings',                           [ProfileController::class, 'updatePreferences'])->name('settings.update');

    // Dossiers
    Route::post('/folders',                            [FolderController::class, 'store'])->name('folders.store');
    Route::get('/folders/{folder}',                    [FolderController::class, 'show'])->name('folders.show');
    Route::delete('/folders/{folder}',                 [FolderController::class, 'destroy'])->name('folders.destroy');

    // Profil
    Route::get('/profile/show',                        [ProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile',                             [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile',                           [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile',                          [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::post('/profile/avatar',                     [ProfileController::class, 'updateAvatar'])->name('profile.avatar');
});

// ═══════════════════════════════
// ESPACE ADMIN
// ═══════════════════════════════
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {

    Route::get('/',                                    [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/users',                               [AdminController::class, 'users'])->name('users');
    Route::get('/users/create',                        [AdminController::class, 'createUser'])->name('users.create');
    Route::post('/users',                              [AdminController::class, 'storeUser'])->name('users.store');
    Route::patch('/users/{user}/toggle-status',        [AdminController::class, 'toggleUserStatus'])->name('users.toggle-status');
    Route::patch('/users/{user}/activate',             [AdminController::class, 'activateUser'])->name('users.activate');
    Route::patch('/users/{user}/role',                 [AdminController::class, 'updateUserRole'])->name('users.role');
    Route::patch('/users/{user}/quota',                [AdminController::class, 'updateUserQuota'])->name('users.quota');
    Route::delete('/users/{user}',                     [AdminController::class, 'destroyUser'])->name('users.destroy');
    Route::get('/documents',                           [AdminController::class, 'documents'])->name('documents');
    Route::post('/documents/{document}/share',         [AdminController::class, 'shareDocument'])->name('documents.share');
    Route::get('/logs',                                [AdminController::class, 'logs'])->name('logs');
    Route::get('/logs/export',                         [AdminController::class, 'exportLogs'])->name('logs.export');
});