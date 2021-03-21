<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\http\Controllers\UserController;
use App\http\Controllers\PlantController;
use App\http\Controllers\Api\AuthController;
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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

// Route::get('/list/{id?}',[UserController::class,'list']);
// Route::post('/signup',[UserController::class,'add']);
// Route::post('/login',[UserController::class,'login']);
// Route::delete('/logout',[UserController::class,'remove']);

// Authentication Routes 
Route::post('/register',[AuthController::class,'register']);
Route::post('/login',[AuthController::class,'login']);
Route::post('/logout',[AuthController::class,'logout']);
Route::get('/user',[AuthController::class,'user'])->middleware('auth:api');

// Product Routes
Route::post('/addPlant',[PlantController::class,'addPlant']);
Route::post('/updatePlant',[PlantController::class,'updatePlant']);
Route::post('/showPlant',[PlantController::class,'showPlant']);
Route::post('/deletePlant',[PlantController::class,'deletePlant']);
Route::post('/allPlants',[PlantController::class,'allPlants']);

