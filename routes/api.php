<?php

use Illuminate\Http\Request;

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

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::get("/events", "EventsController@index")->name("events.index.api");
Route::get("/events/{event}", "EventsController@show")->name("events.show.api");
// Route::middleware('auth:api')->post("/events", "EventsController@store")->name("events.store.api");
Route::post("/events", "EventsController@store")->name("events.store.api");

Route::post("/login", "UsersController@login")->name("users.login.api");
Route::post("/register", "UsersController@store")->name("users.store.api");
Route::middleware('auth:api')->match(['put', 'patch'],"/users", "UsersController@update")->name("users.update.api");
Route::middleware('auth:api')->post("/secret", "UsersController@secret");