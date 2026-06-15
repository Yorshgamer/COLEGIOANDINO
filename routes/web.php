<?php

use App\Http\Controllers\BoletaController;
use App\Http\Controllers\ProfileSignatureController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::inertia('/', 'Welcome')->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::inertia('dashboard', 'Dashboard')->name('dashboard');

    // Ruta para guardar la firma digital del usuario conectado
    Route::post('/profile/signature', [ProfileSignatureController::class, 'update'])->name('profile.signature.update');
    // Rutas del CRUD de Usuarios protegidas por el permiso de Spatie
    Route::post('users/{id}/restore', [UserController::class, 'restore'])->name('users.restore');
    Route::resource('users', UserController::class)
        ->middleware('can:gestionar-usuarios');

    // Nueva ruta estática para el soporte técnico institucional
    Route::get('/soporte', function () {
        return Inertia::render('Soporte', [
            'encargado' => 'Ing. Villena Ochoa Yorshyo Artemio',
            'area' => 'Área de Informática y Sistemas',
            'email' => 'sistemas@andino.edu.pe',
            'whatsapp' => '+51964605500' // <-- Coloca aquí tu número real de soporte
        ]);
    })->name('soporte.index');

    // --- MÓDULO DE BOLETAS DE PAGO, GRATIFICACIONES Y CTS ---
    Route::get('/boletas', [BoletaController::class, 'index'])->name('boletas.index');
    Route::get('/boletas/create', [BoletaController::class, 'create'])->name('boletas.create'); // Nueva vista de carga masiva
    Route::post('/boletas/carga-masiva', [BoletaController::class, 'storeMasivo'])->name('boletas.store_masivo'); // Procesamiento parser
    Route::get('/boletas/{boleta}', [BoletaController::class, 'show'])->name('boletas.show');
    Route::delete('/boletas/{boleta}', [BoletaController::class, 'destroy'])->name('boletas.destroy');
    Route::post('/boletas/{boleta}/notificar', [BoletaController::class, 'notificar'])->name('boletas.notificar');
    Route::post('/boletas/{boleta}/firmar', [BoletaController::class, 'firmar'])->name('boletas.firmar');
    Route::post('/boletas/{boleta}/observar', [BoletaController::class, 'observar'])->name('boletas.observar');
    Route::get('/boletas/{boleta}/descargar-raw', [BoletaController::class, 'descargarRaw'])->name('boletas.descargar_raw');
});

require __DIR__ . '/settings.php';
