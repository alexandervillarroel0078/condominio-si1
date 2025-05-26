<?php

use App\Http\Controllers\LoginController;
use App\Http\Controllers\clienteController;
use App\Http\Controllers\homeController;
use App\Http\Controllers\logoutController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\userController;
use App\Http\Controllers\usuarioController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ServicioController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\residenteController;
use App\Http\Controllers\bitacoraController;
use App\Http\Controllers\empleadoController;
use App\Http\Controllers\CargoEmpleadoController;

use App\Http\Controllers\CuotaController;
use App\Http\Controllers\TipoCuotaController;
use App\Http\Controllers\PagoController;
use App\Http\Controllers\EmpresaController;
use App\Http\Controllers\TipoGastoController;
use App\Http\Controllers\GastoController;
use App\Http\Controllers\UnidadController;

//GESTION DE GASTOS
Route::middleware(['auth'])->group(function () {
    Route::resource('tipo-gastos', TipoGastoController::class);
});
Route::middleware(['auth'])->group(function () {
    Route::resource('gastos', GastoController::class);
});


Route::middleware(['auth'])->group(function () {
    Route::resource('pagos', PagoController::class)->only(['index', 'create', 'store']);
});
Route::resource('empresas', \App\Http\Controllers\EmpresaExternaController::class);

Route::resource('tipos-cuotas', TipoCuotaController::class);

Route::resource('cuotas', CuotaController::class);

Route::get('/cuotasypagos', [CuotaController::class, 'index'])->name('cuotas.index');

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

// Rutas para la gestión de unidades
Route::resource('unidades', UnidadController::class);