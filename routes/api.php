<?php

use App\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        $user = $request->user();
        $user->role; // load role
        
        return $user;
    });

    // clients
    Route::get('/dashboard/client', 'Api\ClientController@index')->middleware('role:' . Role::CLIENT);
    Route::post('/client/appointment/update', 'Api\ClientController@update')->middleware('role:' . Role::CLIENT);
    Route::post('/client/appointment/delete', 'Api\ClientController@delete')->middleware('role:' . Role::CLIENT);
    Route::post('/appointment/reschedule', 'Api\ClientController@reschedule')->middleware('role:' . Role::CLIENT);

    Route::post('/appointment/create', 'Api\AppointmentController@create')->middleware('role:' . Role::CLIENT);

    // lawyers
    Route::get('/dashboard/lawyer', 'Api\LawyerController@index')->middleware('role:' . Role::LAWYER);
    Route::post('/lawyer/appointment/update', 'Api\LawyerController@update')->middleware('role:' . Role::LAWYER);
});


Route::get('/lawyers{order?}/{sort?}/{filter?}', 'Api\UserController@lawyers');
Route::get('/lawyers/{id}', 'Api\UserController@show');
Route::get('/lawyers/{id}/appointment', 'Api\AppointmentController@index');
