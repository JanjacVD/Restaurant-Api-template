<?php

use App\Http\Controllers\Api\v1\GalleryController;
use Illuminate\Http\Request;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\VerifyEmailController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\v1\Menu\FoodItemController;
use App\Http\Controllers\Api\v1\Menu\CategoryItemController;
use App\Http\Controllers\Api\v1\PublicController;
use App\Http\Controllers\Api\v1\Menu\SectionItemController;
use App\Http\Controllers\Api\v1\ReservationCapacityController;
use App\Http\Controllers\Api\v1\ReservationController;
use App\Http\Controllers\Api\v1\Time\DatesOffController;
use App\Http\Controllers\Api\v1\Time\DaysOffController;
use App\Http\Controllers\Api\v1\Time\WorkTimeController;
use App\Http\Controllers\ContactController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});

//General routes
Route::group(['prefix' => 'v1'], function () {

    Route::post('/contact', [ContactController::class, 'send'])
        ->name('send-mail');

    Route::post('/reservation-make', [ReservationController::class, 'newReservation'])
        ->name('reservation.make');

    Route::post('/reservation-confirm', [ReservationController::class, 'confirmReservation'])
        ->name('reservation.confirm');

    Route::post('/reservation-resend-pending', [ReservationController::class, 'resendConfirmationEmail'])
        ->name('reservation.resend-pending');

    Route::post('/reservation-delete', [ReservationController::class, 'delete'])
        ->name('reservation.delete');

    Route::get('/landing', [WorkTimeController::class, 'index'])
        ->name('public.landing');

    Route::get('/menu', [PublicController::class, 'menu'])
        ->name('public.menu');
    
    Route::get('/booking', [PublicController::class, 'reservation'])
        ->name('public.reservation');

    Route::get('/gallery', [GalleryController::class, 'index'])
        ->name('public.gallery');

});

//Admin panel routes

//User non-protected routes
Route::group(['middleware' => 'guest', 'prefix' => 'v1'], function () {

    Route::post('/register', [RegisteredUserController::class, 'store'])
        ->name('register');

    Route::post('/login', [AuthenticatedSessionController::class, 'store'])
        ->middleware('guest')
        ->name('login');
    Route::post('/forgot-password', [PasswordResetLinkController::class, 'store'])
        ->middleware('guest')
        ->name('password.email');

    Route::post('/reset-password', [NewPasswordController::class, 'store'])
        ->middleware('guest')
        ->name('password.update');
});



//Protected routes 
Route::group(['middleware' => 'auth:sanctum', 'prefix' => 'v1'], function () {

    Route::get('/verify-email/{id}/{hash}', [VerifyEmailController::class, '__invoke'])
        ->middleware(['signed'])
        ->name('verification.verify');

    Route::post('/email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
        ->name('verification.send');

    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])
        ->name('logout');


    //Menu routes

    //Food items
    Route::get('/food', [FoodItemController::class, 'index'])
        ->name('food.index');

    Route::get('/food/{id}', [FoodItemController::class, 'edit'])
        ->name('food.edit');

    Route::put('/food/{id}', [FoodItemController::class, 'update'])
        ->name('food.update');

    Route::post('/food/store', [FoodItemController::class, 'store'])
        ->name('food.store');

    Route::delete('/food/{id}', [FoodItemController::class, 'destroy'])
        ->name('food.destroy');

    //Category items

    Route::get('/category', [CategoryItemController::class, 'index'])
        ->name('category.index');

    Route::get('/category/{id}/show', [CategoryItemController::class, 'show'])
        ->name('category.show');

    Route::get('/category/{id}', [CategoryItemController::class, 'edit'])
        ->name('category.edit');

    Route::put('/category/{id}', [CategoryItemController::class, 'update'])
        ->name('category.update');

    Route::post('/category/store', [CategoryItemController::class, 'store'])
        ->name('category.store');

    Route::delete('/category/{id}', [CategoryItemController::class, 'destroy'])
        ->name('category.destroy');

    //Section Items

    Route::get('/section', [SectionItemController::class, 'index'])
        ->name('section.index');

    Route::get('/section/{id}/show', [SectionItemController::class, 'show'])
        ->name('section.show');

    Route::get('/section/{id}', [SectionItemController::class, 'edit'])
        ->name('section.edit');

    Route::put('/section/{id}', [SectionItemController::class, 'update'])
        ->name('section.update');

    Route::post('/section/store', [SectionItemController::class, 'store'])
        ->name('section.store');

    Route::delete('/section/{id}', [SectionItemController::class, 'destroy'])
        ->name('section.destroy');

    //Work time

    Route::get('/work-time', [WorkTimeController::class, 'index'])
        ->name('work-time.index');

    Route::get('/work-time/{id}', [WorkTimeController::class, 'edit'])
        ->name('work-time.edit');

    Route::put('/work-time/{id}', [WorkTimeController::class, 'update'])
        ->name('work-time.update');

    Route::post('/work-time/store', [WorkTimeController::class, 'store'])
        ->name('work-time.store');

    //Days off

    Route::get('/days-off', [DaysOffController::class, 'index'])
        ->name('days-off.index');

    Route::post('/days-off/store', [DaysOffController::class, 'store'])
        ->name('days-off.store');

    Route::delete('/days-off/{id}', [DaysOffController::class, 'destroy'])
        ->name('days-off.update');

    //Dates off

    Route::get('/dates-off', [DatesOffController::class, 'index'])
        ->name('dates-off.index');

    Route::post('/dates-off/store', [DatesOffController::class, 'store'])
        ->name('dates-off.store');

    Route::delete('/dates-off/{id}', [DatesOffController::class, 'destroy'])
        ->name('dates-off.update');

    //Reservations

    Route::get('/reservations', [ReservationController::class, 'index'])
        ->name('reservations.index');

    Route::get('/reservations-today', [ReservationController::class, 'today'])
        ->name('reservations.today');

    Route::post('/reservations-show', [ReservationController::class, 'show'])
        ->name('reservations.show');

    Route::get('/reservations-print-today', [ReservationController::class, 'print_today'])
        ->name('reservations.print-today');

    Route::post('/reservations-print-date', [ReservationController::class, 'print_date'])
        ->name('reservations.print-date');

    Route::delete('/reservations-cancel', [ReservationController::class, 'cancel'])
        ->name('reservations.cancel');

    //Reservation settings

    Route::get('/reservations-settings', [ReservationCapacityController::class, 'index'])
    ->name('reservations.settings');

    Route::post('/reservations-settings-create', [ReservationCapacityController::class, 'store'])
    ->name('reservations.settings-create');

    Route::get('/reservations-settings-edit', [ReservationCapacityController::class, 'edit'])
    ->name('reservations.settings-edit');

    Route::put('/reservations-settings-update', [ReservationCapacityController::class, 'update'])
    ->name('reservations.settings-update');

});
