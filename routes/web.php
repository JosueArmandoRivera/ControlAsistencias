<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Generales\LoginController;
use App\Http\Controllers\Generales\DashboardController;
use App\Http\Controllers\Administrador\RolesController;
use App\Http\Controllers\Generales\ConfiguracionNotificacionesController;
use App\Http\Controllers\Generales\NotificacionesController;
use App\Http\Controllers\Administrador\PerfilAdministradorController;
use App\Http\Controllers\Generales\PageErrorController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::GET('/RecuperarContrasena', [LoginController::class, 'recuperarContrasena'])->name('RecuperarContrasena');
Route::GET('/', [LoginController::class, 'retornarVista'])->name('login');          //Ruta a la pagina de login
Route::POST('/logout', [LoginController::class, 'logout'])->name('LogOut');  //Ruta cuando cerramos la sesión
Route::POST('/IniciarSesion', [LoginController::class, 'login'])->name('IniciarSesion');  //Ruta cuando se insertan las credenciales 


Route::controller(DashboardController::class)->group(function () {
    Route::middleware('auth', 'prohibirRetroceso')->group(function () {
        Route::GET('/Dashboard', 'index')->name('index');  //Ruta para retornar la pantalla principal
        Route::GET('/Dashboard/informacionInicial', 'informacionInicial')->name('index.informacionInicial');                          //Ruta para retornar la pantalla principal
        Route::GET('/Dashboard/informacionGraficas', 'informacionGraficas')->name('index.informacionGraficas');                          //Ruta para retornar la pantalla principal
    });
});

Route::controller(NotificacionesController::class)->group(function () {
    Route::middleware('auth')->group(function () {
        Route::GET('/notificaciones', [NotificacionesController::class, 'index'])->name('notificaciones.index'); //Ruta para retornar la pantalla principal
        Route::GET('/notificaciones/armarTabla', [NotificacionesController::class, 'armarTabla'])->name('notificaciones.armarTabla');     //Ruta para consultar y contruir la tabla
        Route::POST('/notificaciones/marcarLeidos', [NotificacionesController::class, 'marcarLeidos'])->name('notificaciones.marcarLeido');
        Route::POST('/notificaciones/eliminar', [NotificacionesController::class, 'destroy'])->name('notificaciones.destroy');
    });
});

Route::controller(ConfiguracionNotificacionesController::class)->group(function () {
    Route::middleware('auth')->group(function () {
        Route::GET('/configuracionnotificaciones', 'index')->name('config-notificaciones.index'); //Ruta para retornar la pantalla principal
        Route::POST('/configuracionnotificaciones', 'update')->name('config-notificaciones.update'); //Ruta para retornar la pantalla principal
        Route::POST('/configuracionnotificaciones/dias', 'update_dias')->name('config-notificaciones.update-dias'); //Ruta para retornar la pantalla principal
        Route::POST('/configuracionnotificaciones/show', 'show')->name('config-notificaciones.show'); //Ruta para retornar la pantalla principal
    });
});

Route::middleware('auth', 'prohibirRetroceso')->group(function () {
    Route::GET('/rol', [RolesController::class, 'index'])->name('rol.index');                             //Ruta para retornar la pantalla principal
    Route::GET('/rol/armarTabla', [RolesController::class, 'armarTabla'])->name('rol.armarTabla');        //Ruta para consultar y contruir la tabla
    Route::POST('/rol/store', [RolesController::class, 'store'])->name('rol.store');                      //Ruta para realizar un registro
    Route::GET('/rol/consultar', [RolesController::class, 'show'])->name('rol.show');                     //Ruta para consultar un registro
    Route::POST('/rol/modificar', [RolesController::class, 'update'])->name('rol.update');                //Ruta para actualizar un registro
    Route::POST('/rol/eliminar', [RolesController::class, 'destroy'])->name('rol.destroy');               //Ruta para eliminar un registro 
});

Route::middleware('auth')->group(function () {
    Route::POST('/primerCambioContrasena', [PerfilAdministradorController::class, 'update_password'])->name('perfil-administrador.update-password');                  //Ruta para consultar un registro
});

Route::GET('/404PermisoDenegado', [PageErrorController::class, 'index'])->name('error.index');
Route::GET('/404PaginaNoEncontrada', [PageErrorController::class, 'noEncontrada'])->name('error.noEncontrada');
Route::GET('/500ErrorServidor', [PageErrorController::class, 'error500'])->name('error.error500');  //Este tiene una liga o URL, sin embargo solo es para la exposicion de la visualización de la pagina