<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\UserController;
use App\Http\Controllers\PlantController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;

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
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout']);
Route::get('/user', [AuthController::class, 'user'])->middleware('auth:api');

// Product Routes
Route::post('/addPlant', [PlantController::class, 'addPlant']);
Route::put('/updatePlant', [PlantController::class, 'updatePlant']);
Route::get('/showPlant', [PlantController::class, 'showPlant']);
Route::post('/deletePlant', [PlantController::class, 'deletePlant']);
Route::get('/allPlants', [PlantController::class, 'allPlants']);
// plant categories and types
Route::get('/categaries', [PlantController::class, 'allPlantcategories']);
Route::get('/types', [PlantController::class, 'allPlanttypes']);

Route::post('add_to_cart', [CartController::class, 'add_to_cart']);
Route::get('show_cart', [CartController::class, 'show_cart']);
Route::post('delete_from_cart', [CartController::class, 'delete_from_cart']);

//Route::post('order_from_cart', [CartController::class, 'order_from_cart']);

// Direct order route

Route::post('add_order', [OrderController::class, 'add_order']);
// view single (current order ) of user
Route::get('view_order', [OrderController::class, 'view_order']);
// view all orders of a user
Route::get('all_order', [OrderController::class, 'view_all_orders']);
Route::delete('delete_order', [OrderController::class, 'delete_order']);
Route::put('update_order', [OrderController::class, 'update_order']);
