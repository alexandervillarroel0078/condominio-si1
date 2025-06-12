<?php

use App\Http\Controllers\LoginController;
use App\Http\Controllers\clienteController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LogoutController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UsuarioController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\ResidenteController;
use App\Http\Controllers\BitacoraController;
use App\Http\Controllers\EmpleadoController;
use App\Http\Controllers\CargoEmpleadoController;
use App\Http\Controllers\MantenimientosController;
use App\Http\Controllers\CuotaController;
use App\Http\Controllers\TipoCuotaController;
use App\Http\Controllers\PagoController;
use App\Http\Controllers\EmpresaController;
use App\Http\Controllers\TipoGastoController;
use App\Http\Controllers\GastoController;
use App\Http\Controllers\AreaComunController;
use App\Http\Controllers\ReservaController;
use App\Http\Controllers\UnidadController;
use App\Http\Controllers\MultaController;
use App\Http\Controllers\VisitaController;
use App\Http\Controllers\NotificacionController;
//gestion de multas
Route::middleware(['auth'])->group(function () {
    Route::resource('multas', MultaController::class)->parameters([
        'multas' => 'multa'
    ]);
});

// gestión de áreas comunes y reservas
Route::middleware(['auth'])->group(function () {
    Route::resource('areas-comunes', AreaComunController::class)->parameters([
        'areas-comunes' => 'areaComun'
    ]);
    Route::resource('reservas', ReservaController::class);
});
Route::get('/api/horas-libres', [ReservaController::class, 'horasLibres']);

// GESTIÓN DE VISITAS
Route::middleware(['auth'])->group(function () {
    Route::resource('visitas', VisitaController::class);

    // Ruta para mostrar formulario de validación
    Route::get('/validar-codigo', [VisitaController::class, 'mostrarValidarCodigo'])
        ->name('visitas.mostrar-validar-codigo');

    // Rutas específicas para guardias (control en controlador)
    Route::post('/visitas/validar-codigo', [VisitaController::class, 'validarCodigo'])
        ->name('visitas.validar-codigo');
    Route::post('/visitas/{visita}/entrada', [VisitaController::class, 'registrarEntrada'])
        ->name('visitas.entrada');
    Route::post('/visitas/{visita}/salida', [VisitaController::class, 'registrarSalida'])
        ->name('visitas.salida');
    Route::get('/panel-guardia', [VisitaController::class, 'panelGuardia'])
        ->name('visitas.panel-guardia');
    Route::get('/buscar-codigo', [VisitaController::class, 'buscarPorCodigo'])
        ->name('visitas.buscar-codigo');
});

// GESTIÓN DE GASTOS
Route::middleware(['auth'])->group(function () {
    Route::resource('tipo-gastos', TipoGastoController::class);
});
Route::middleware(['auth'])->group(function () {
    Route::resource('gastos', GastoController::class);
});


Route::resource('empresas', \App\Http\Controllers\EmpresaExternaController::class);
Route::resource('tipos-cuotas', TipoCuotaController::class);
Route::resource('cuotas', CuotaController::class);
Route::get('/cuotasypagos', [CuotaController::class, 'index'])->name('cuotas.index');

// Pago de una cuota
Route::get('/pagos/create/cuota/{cuota}', [PagoController::class, 'createCuota'])
     ->name('pagos.create.cuota')
     ->middleware('auth');

// Pago de una multa
Route::get('/pagos/create/multa/{multa}', [PagoController::class, 'createMulta'])
     ->name('pagos.create.multa')
     ->middleware('auth');

Route::middleware(['auth'])->group(function () {
    Route::resource('pagos', PagoController::class)->only(['index',  'store']);
});

Route::middleware(['auth'])->group(function () {
    Route::get('/mis-cuotas', [PagoController::class, 'misCuotas'])->name('pagos.mis_cuotas');
    Route::post('/pagos/qr', [PagoController::class, 'pagoQR'])->name('pagos.qr');
    Route::post('/pagos/stripe', [PagoController::class, 'pagoStripe'])->name('pagos.stripe');
});

Route::get('/stripe/success/{cuota}', [PagoController::class, 'stripeSuccess'])->name('pagos.stripe.success');
Route::get('/stripe/cancel', function () {
    return redirect()->route('pagos.mis_cuotas')->with('error', 'Pago cancelado.');
})->name('pagos.stripe.cancel');




















Route::prefix('empleados/cargo')->group(function () {
    Route::get('/', [CargoEmpleadoController::class, 'index'])->name('cargos.index');
    Route::get('/crear', [CargoEmpleadoController::class, 'create'])->name('cargos.create');
    Route::post('/', [CargoEmpleadoController::class, 'store'])->name('cargos.store');
    Route::get('/{id}/editar', [CargoEmpleadoController::class, 'edit'])->name('cargos.edit');
    Route::put('/{id}', [CargoEmpleadoController::class, 'update'])->name('cargos.update');
    Route::delete('/{id}', [CargoEmpleadoController::class, 'destroy'])->name('cargos.destroy');
});

Route::get('/', [homeController::class, 'index'])->name('panel');
Route::get('/panel', [homeController::class, 'index']);

Route::resource('bitacora', BitacoraController::class);
Route::resource('roles', RoleController::class)->middleware('auth');

Route::resources([
    'users' => usuarioController::class,
    'residentes' => residenteController::class,
]);
Route::resource('empleados', App\Http\Controllers\empleadoController::class);
Route::resource('mantenimientos', App\Http\Controllers\MantenimientoController::class);

Route::get('/login', [LoginController::class, 'index'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::get('/logout', [logoutController::class, 'logout'])->name('logout');
Route::post('/login', [LoginController::class, 'login'])->name('login');

Route::get('/401', function () {
    return view('pages.401');
});
Route::get('/404', function () {
    return view('pages.404');
});
Route::get('/500', function () {
    return view('pages.500');
});
Route::get('/admin', function () {
    // Solo administradores
})->middleware('role:ADMINISTRADOR');
Route::get('/prueba-permiso', function () {
    return 'Tienes permiso';
})->middleware(['auth', 'permission:ver-role']);

Route::resource('unidades', UnidadController::class);

Route::resource('notificaciones', NotificacionController::class)->parameters([
    'notificaciones' => 'notificacion',
]);

















Route::get('/mi-perfil', [UsuarioController::class, 'miPerfil'])->name('users.perfil');

