<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    UserController,
    AuthController,
    PacienteController,
    MedicoController,
    EspecialidadController,
    TratamientoController,
    CitaController,
    HistorialCitaController,
    CalendarioController,
    PagoController,
    ReporteController,
    ResumenController,
    TendenciaController,
    OdontogramaController,
    ConfiguracionController,
    DashboardController
};

// Rutas públicas (login/logout)
Route::get('/', [AuthController::class, 'loginForm'])->name('login.form');
Route::get('/login', [AuthController::class, 'loginForm'])->name('login.form');
Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Rutas protegidas con middleware auth
Route::middleware(['auth'])->group(function () {

    // Dashboard general (admin)
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Recursos
    Route::resource('usuarios', UserController::class);
    Route::get('/usuarios/{usuario}/edit', [UserController::class, 'edit'])->name('usuarios.edit');

    Route::resource('pacientes', PacienteController::class);
    Route::resource('medicos', MedicoController::class);
    Route::resource('especialidades', EspecialidadController::class);
    Route::resource('tratamientos', TratamientoController::class);
    Route::resource('citas', CitaController::class);
    Route::resource('historial', HistorialCitaController::class);
    Route::resource('calendario', CalendarioController::class);
    Route::resource('pagos', PagoController::class);
    
    Route::resource('configuracion', ConfiguracionController::class)->only(['index', 'update']);


    Route::get('/api/eventos', [CalendarioController::class, 'apiEventos'])->name('api.eventos');

    // Odontograma
    Route::get('/odontograma', [OdontogramaController::class, 'index'])->name('odontograma.index');
    Route::get('/odontograma/{paciente}', [OdontogramaController::class, 'show'])->name('odontograma.show');
    Route::post('/odontograma/store', [OdontogramaController::class, 'store'])->name('odontograma.store');

    // Resumen y tendencias
    Route::get('/resumen', [ResumenController::class, 'index'])->name('resumen.index');
    Route::get('/tendencias', [TendenciaController::class, 'index'])->name('tendencias.index');

    // Reportes
    Route::get('/reportes', [ReporteController::class, 'resumenGeneral'])->name('reportes.resumen');
    Route::get('/reportes/pdf', [ReporteController::class, 'generarPDF'])->name('reportes.pdf');
    Route::post('/reportes/citas-rango', [ReporteController::class, 'citasPorRango'])->name('reportes.citasRango');

    // Dashboards específicos por rol
    Route::get('/medico/dashboard', function () {
        return view('dashboard.medico'); // Revisa que exista resources/views/dashboard/medico.blade.php
    })->name('medico.dashboard');

    Route::get('/paciente/dashboard', function () {
        return view('dashboard.paciente'); // Revisa que exista resources/views/dashboard/paciente.blade.php
    })->name('paciente.dashboard');
});
