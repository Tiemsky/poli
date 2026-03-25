<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EnrollementController;
use App\Http\Controllers\ProfileController;
use App\Models\Commune;
use Illuminate\Foundation\Application;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});


Route::middleware(['auth', 'verified'])->group(function () {
  Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');
  Route::get('enrollement/selection', [EnrollementController::class, 'selection'])->name('enrollment.selection');
  Route::get('enrollement/{selectedType}', [EnrollementController::class, 'showForm'])->name('enrollment.showForm');
  Route::post('enrollement', [EnrollementController::class, 'store'])->name('enrollment.store');
  Route::get('enrollement/{enrollement}/show', [EnrollementController::class, 'show'])->name('enrollement.show');
});
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('cities/{cityId}/communes', function ($cityId) {
  $communes =  Commune::where('city_id', $cityId)->orderBy('name', 'ASC')->get();
  return response()->json([
    'success' =>true,
    'data'    => $communes
  ], 200);
});

require __DIR__.'/auth.php';
