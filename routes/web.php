<?php

use App\Http\Controllers\BoletaController;
use App\Http\Controllers\ProfileSignatureController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WebController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

// --- RUTAS PÚBLICAS (EXTERNAS AL LOGIN) ---
Route::get('/', [WebController::class, 'home'])->name('home');
Route::get('/presentacion', [WebController::class, 'presentacion'])->name('web.presentacion');
Route::get('/iglesia-metodista', [WebController::class, 'iglesiaMetodista'])->name('web.iglesia_metodista');
Route::get('/mision-vision', [WebController::class, 'misionVision'])->name('web.mision_vision');
Route::get('/historia', [WebController::class, 'historia'])->name('web.historia');
Route::get('/orientacion', [WebController::class, 'orientacion'])->name('web.orientacion');
Route::get('/capellania', [WebController::class, 'capellania'])->name('web.capellania');
Route::get('/coordinacion', [WebController::class, 'coordinacion'])->name('web.coordinacion');
Route::get('/psicologia', [WebController::class, 'psicologia'])->name('web.psicologia');
Route::get('/actividades', [WebController::class, 'actividades'])->name('web.actividades');
Route::get('/admision', [WebController::class, 'admision'])->name('web.admision');
Route::get('/matricula', [WebController::class, 'matricula'])->name('web.matricula');
Route::get('/contacto', [WebController::class, 'contacto'])->name('web.contacto');
Route::get('/servicio', [WebController::class, 'servicio'])->name('web.servicio');

// --- RUTAS PROTEGIDAS (SISTEMA INTERNO) ---
Route::middleware(['auth', 'verified'])->group(function () {
    Route::inertia('dashboard', 'Dashboard')->name('dashboard');

    // Ruta para guardar la firma digital del usuario conectado
    Route::post('/profile/signature', [ProfileSignatureController::class, 'update'])->name('profile.signature.update');
    // Rutas del CRUD de Usuarios protegidas por el permiso de Spatie
    Route::post('users/{id}/restore', [UserController::class, 'restore'])->name('users.restore');
    Route::post('/users/{id}/regenerar-credenciales', [UserController::class, 'regenerarCredenciales'])->name('users.regenerar_credenciales');
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
    Route::get('/mis-boletas', [BoletaController::class, 'misBoletas'])->name('boletas.mis_boletas');
    Route::get('/boletas/create', [BoletaController::class, 'create'])->name('boletas.create'); // Nueva vista de carga masiva
    Route::post('/boletas/carga-masiva', [BoletaController::class, 'storeMasivo'])->name('boletas.store_masivo'); // Procesamiento parser
    Route::post('/boletas/{id}/notificar', [BoletaController::class, 'notificarPersonal']);
    Route::get('/boletas/{boleta}', [BoletaController::class, 'show'])->name('boletas.show');
    Route::delete('/boletas/{boleta}', [BoletaController::class, 'destroy'])->name('boletas.destroy');
    Route::post('/boletas/{boleta}/firmar', [BoletaController::class, 'firmar'])->name('boletas.firmar');
    Route::post('/boletas/{boleta}/observar', [BoletaController::class, 'observar'])->name('boletas.observar');
    Route::get('/boletas/{boleta}/descargar-raw', [BoletaController::class, 'descargarRaw'])->name('boletas.descargar_raw');
    Route::get('/boletas/{boleta}/pdf', [BoletaController::class, 'verPdf'])->name('boletas.pdf');
    Route::get('/boletas/{boleta}/trabajador-firma', [BoletaController::class, 'renderTrabajadorSignature'])->name('boletas.trabajador.firma');

    // Ejecuta la descarga física del archivo
    Route::get('/boletas/{boleta}/descargar', [BoletaController::class, 'descargarPdf'])->name('boletas.descargar');
    Route::post('/profile/signature', [ProfileSignatureController::class, 'update'])->name('profile.signature.update');

    // 🎯 AGREGA ESTA LÍNEA DEBAJO PARA CONECTAR EL RENDERIZADOR
    Route::get('/profile/signature/image', [ProfileSignatureController::class, 'renderImage'])->name('profile.signature.image');
    Route::get('/administracion/firma/sello', [ProfileSignatureController::class, 'renderAdminSignature'])->name('admin.signature.sello');

    // 🎯 Endpoint para la Firma del Trabajador dueño de esta boleta
});

require __DIR__ . '/settings.php';
