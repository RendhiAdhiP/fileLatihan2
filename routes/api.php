<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\FormController;
use App\Http\Controllers\Api\QuestionController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::prefix('/v1')->group(function(){
    Route::prefix('/auth')->group(function(){
        Route::post('/login',[AuthController::class, 'login']);
        Route::post('/logout',[AuthController::class, 'logout'])->middleware(['auth:sanctum']);
    });

    Route::middleware('auth:sanctum')->group(function(){
        Route::post('/forms',[FormController::class, 'createForm']);
        Route::get('/forms',[FormController::class, 'getForms']);
        Route::get('/forms/{slug}',[FormController::class, 'detail']);
        
        Route::post('/forms/{slug}/questions',[QuestionController::class, 'addQuestion']);
        Route::delete('/forms/{slug}/questions/{id}',[QuestionController::class, 'removeQuest']);
    });
});