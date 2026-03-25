<?php

use App\Http\Controllers\DocumentController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes - Documents Sécurisés
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->prefix('documents')->group(function () {

    // Voir (inline)
    Route::get('/{enrollementKey}/{filename}', [DocumentController::class, 'show'])
        ->name('documents.show');

    // Télécharger (download)
    Route::get('/{enrollementKey}/{filename}/download', [DocumentController::class, 'download'])
        ->name('documents.download');

    // Preview image
    Route::get('/{enrollementKey}/{filename}/preview', [DocumentController::class, 'preview'])
        ->name('documents.preview');

});
