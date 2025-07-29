<?php

use App\Http\Controllers\Api\AdminController;
use App\Http\Controllers\Api\Authcontroller;
use App\Http\Controllers\Api\CommentController;
use App\Http\Controllers\Api\ProjectControlller;
use App\Http\Controllers\Api\TaksController as ApiTaksController;
use App\Http\Controllers\TaksCOntroller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\isAdmin;
use App\Http\Middleware\isUsers;


// Routes Public
Route::post('/register', [Authcontroller::class, 'register']);
Route::post('/login', [Authcontroller::class, 'login']);

// Route Privee
Route::middleware(['auth:api'])->group(function () {
    //  Route acceder par users et admin
    Route::post('/logout', [Authcontroller::class, 'logout']);
    // Route Projet
    Route::get('/projet', [ProjectControlller::class, 'index']);
    Route::post('/projet', [ProjectControlller::class, 'store']);
    Route::get('/projet/{id}', [ProjectControlller::class, 'show']);
    Route::put('/projet/{id}', [ProjectControlller::class, 'update']);
    Route::delete('/projet/{id}', [ProjectControlller::class, 'destroy']);
    // Route Tache
    Route::get('/tache', [ApiTaksController::class, 'index']);
    Route::post('/tache', [ApiTaksController::class, 'store']);
    Route::get('/tache/{id}', [ApiTaksController::class, 'show']);
    Route::put('/tache/{id}', [ApiTaksController::class, 'update']);
    Route::delete('/tache/{id}', [ApiTaksController::class, 'destroy']);
    // Route Commentaire
    Route::get('/commentaire', [CommentController::class, 'index']);
    Route::post('/commentaire', [CommentController::class, 'store']);
    Route::get('/commentaire/{id}', [CommentController::class, 'show']);
    Route::put('/commentaire/{id}', [CommentController::class, 'update']);
    Route::delete('/commentaire/{id}', [CommentController::class, 'destroy']);

    // routes reverse aux admin
    Route::middleware([isAdmin::class])->group(function () {
        Route::get('/getUsers', [Authcontroller::class, 'getUsers']);
        Route::get('/statistique', [AdminController::class, 'index']);
        Route::get('/countusers', [AdminController::class, 'getUserCount']);
        Route::get('/countprojet', [AdminController::class, 'getProjetCount']);
        Route::get('/counttache', [AdminController::class, 'getTacheCount']);
        Route::get('/tacherecent', [AdminController::class, 'getRecentTasks']);
        Route::get('/projetrecent', [AdminController::class, 'getRecentProjects']);
    });

    // routes reversee au users
    Route::middleware([isUsers::class])->group(function () {});
});
