<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\PostController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/register',[AuthController::class,'register']);
Route::post('/login',[AuthController::class,'login']);

Route::middleware('auth:sanctum')->group(function(){

Route::post('/logout',[AuthController::class,'logout']);

//blog api points are here
Route::post('/add-post',[PostController::class,'addNewPost']);
Route::put('/edit-post',[PostController::class,'updatePost']);
Route::put('/post/{post_id}',[PostController::class,'updatePost2']);
Route::get('/all-posts',[PostController::class,'getAllPosts']);

});
