<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\MealController;
use App\Http\Controllers\CommentController;



Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::post('/login', [LoginController::class, 'login']);
Route::post('/register', [RegisterController::class, 'register']);
Route::post('/meal', [MealController::class, 'store']);
Route::get('/api/userMeal', [MealController::class, 'usersMeal']);
Route::delete('/favoritemeals', [MealController::class,'deletefavoritemeal']);
Route::delete('/deleteuserMeal', [MealController::class,'deleteMeal']);
Route::put('/meal/{id}', [MealController::class,'updateMeal']);
Route::get('/meal/{id}', [MealController::class,'showMeal']);
Route::post('/comments', [CommentController::class, 'addComment']);
Route::get('/comments/{mealId}', [CommentController::class, 'getComments']);
Route::resource('meal', MealController::class);
Route::middleware('auth:sanctum')->post('/meal', [MealController::class, 'store']);
Route::middleware('auth:sanctum')->get('/userMeal', [MealController::class, 'usersMeal']);
Route::middleware('auth:sanctum')->post('/favoritemeals', [MealController::class, 'addToFavorite']);
Route::middleware('auth:sanctum')->post('/logout', [LoginController::class,'logout']);
Route::middleware('auth:sanctum')->delete('/favoritemeals/{mealId}', [MealController::class, 'deletefavoritemeal']);
Route::middleware('auth:sanctum')->get('/favoritemeals', [MealController::class, 'userFavoriteMeal']);
Route::middleware('auth:sanctum')->put('/meal/{id}', [MealController::class, 'updateMeal']);
Route::middleware('auth:sanctum')->delete('/deleteuserMeal/{id}', [MealController::class, 'deleteMeal']);
Route::middleware('auth:sanctum')->get('/meal/{id}', [MealController::class, 'showMeal']);
Route::middleware('auth:sanctum')->post('/comments', [CommentController::class, 'addComment']);
Route::middleware('auth:sanctum')->get('/comments/{mealId}', [CommentController::class, 'getComments']);
Route::middleware('auth:sanctum')->get('/user/{id}', [CommentController::class, 'getnameuser']);
