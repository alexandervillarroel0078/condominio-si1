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
use App\Http\Controllers\VisitaController; 
use App\Http\Controllers\RegistroSeguridadController;
use App\Http\Controllers\MultaController;
use App\Http\Controllers\VisitaController;
use App\Http\Controllers\NotificacionController;
use App\Http\Controllers\ComunicadoController;
use App\Http\Controllers\InventarioController;
use App\Http\Controllers\ReclamoController;

//gestion de reclamos
Route::middleware(['auth'])->group(function () {
    Route::resource('reclamos', ReclamoController::class)->parameters([
        'reclamos' => 'reclamo'
    ]);
});
Route::patch('reclamos/{reclamo}/respuesta', [App\Http\Controllers\ReclamoController::class, 'respuesta'])
     ->name('reclamos.respuesta')
     ->middleware('auth');


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
    Route::resource('reservas', ReservaController::class)->parameters([
        'reservas' => 'reserva'
    ]);
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

// GESTIÓN DE SEGURIDAD Y VIGILANCIA
Route::middleware(['auth'])->group(function () {
    // Rutas básicas CRUD
    Route::resource('seguridad', RegistroSeguridadController::class);
    
    // Rutas específicas para resolución de incidentes (control en controlador)
    Route::post('/seguridad/{id}/resolver', [RegistroSeguridadController::class, 'resolver'])
        ->name('seguridad.resolver');
    
    // Rutas específicas para residentes
    Route::get('/reportar-incidente', [RegistroSeguridadController::class, 'reportarIncidente'])
        ->name('seguridad.reportar-incidente');
    
    Route::get('/mis-reportes', [RegistroSeguridadController::class, 'misReportes'])
        ->name('seguridad.mis-reportes');
    
    // Ruta para guardar incidente rápido (POST del formulario de reportar-incidente)
    Route::post('/reportar-incidente', [RegistroSeguridadController::class, 'store'])
        ->name('seguridad.reportar-incidente.store');
    // Ruta para marcar en revisión
    Route::post('/seguridad/{registro}/revisar', [RegistroSeguridadController::class, 'marcarEnRevision'])
    ->name('seguridad.marcar-revision');
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
Route::post('/pagos/qr-multa', [PagoController::class, 'pagoQRMulta'])
     ->name('pagos.qr.multa')
     ->middleware('auth');
Route::post('/pagos/stripe/multa', [PagoController::class, 'pagoStripeMulta'])
     ->name('pagos.stripe.multa')
     ->middleware('auth');
Route::get('/stripe/success/multa/{multa}', [PagoController::class, 'stripeSuccessMulta'])
     ->name('pagos.stripe.success.multa');

Route::middleware(['auth'])->group(function () {
    Route::resource('pagos', PagoController::class)->only(['index',  'store']);
});


Route::get('/pagos/comprobante/{pago}', [PagoController::class, 'comprobante'])->name('pagos.comprobante'); // ✅ CORRECTO

Route::middleware(['auth'])->group(function () {
    Route::get('/mis-cuotas', [PagoController::class, 'misCuotas'])->name('pagos.mis_cuotas');
    Route::post('/pagos/qr', [PagoController::class, 'pagoQR'])->name('pagos.qr');
    Route::post('/pagos/stripe', [PagoController::class, 'pagoStripe'])->name('pagos.stripe');
});

Route::get('/stripe/success/{cuota}', [PagoController::class, 'stripeSuccess'])->name('pagos.stripe.success');
Route::get('/stripe/cancel', function () {
    return redirect()->route('pagos.mis_cuotas')->with('error', 'Pago cancelado.');
})->name('pagos.stripe.cancel');


Route::middleware(['auth'])->group(function () {
    Route::resource('inventario', InventarioController::class);
});
Route::get('/inventario/{id}', [InventarioController::class, 'show'])->name('inventario.show');

Route::get('reservas/{reserva}/verificar-inventario', [ReservaController::class, 'verificarInventario'])->name('reservas.verificar-inventario');
Route::post('/reservas/{reserva}/verificar-inventario', [ReservaController::class, 'guardarVerificacion'])->name('reservas.guardar-verificacion');


Route::get('/inventario', [InventarioController::class, 'index'])->name('inventario.index');
Route::get('/inventario/filtrar', [InventarioController::class, 'filtrar'])->name('inventario.filtrar');
 
Route::get('/pdf-test', [InventarioController::class, 'testPdf']);
Route::get('/inventario/exportar/csv', [InventarioController::class, 'exportarCsv'])
      ->name('inventario.exportar.csv');

Route::get('/inventario/exportar/pdf', [InventarioController::class, 'exportarPdf'])
      ->name('inventario.exportar.pdf');
 






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

Route::resource('comunicados', ComunicadoController::class);

Route::post('/notificaciones/{id}/leer', [NotificacionController::class, 'marcarLeida'])->name('notificaciones.marcarLeida');
Route::post('/notificaciones/{id}/ver', [NotificacionController::class, 'ver'])->name('notificaciones.ver');





Route::get('/mi-perfil', [UsuarioController::class, 'miPerfil'])->name('users.perfil');
