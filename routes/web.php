<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\v1\ReservationController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return redirect(env('FRONTEND_URL'));
});


Route::get('/reservation-make', [ReservationController::class, 'confirmReservation'])
->name('reservation.confirm');