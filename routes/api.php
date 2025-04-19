<?php

use App\Http\Controllers\Api\JwtAuthController;
use App\Http\Controllers\PostController;
use App\Jobs\TranslateJob;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::group(['prefix' => "auth"], function(){
    
    Route::post('signup', [JwtAuthController::class, 'register']);
    Route::post('signin', [JwtAuthController::class, 'login']);
    Route::get('profile', [JwtAuthController::class, 'profile'])->middleware('jwtauthmiddleware');
    Route::post('logout', [JwtAuthController::class, 'logout'])->middleware('jwtauthmiddleware');
    
});


Route::group(['middleware' => "jwtauthmiddleware"], function(){

    Route::resource('posts', PostController::class)->except(['create', 'edit']);
    
});



