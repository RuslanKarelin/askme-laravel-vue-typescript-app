<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use \App\Http\Controllers\QuestionController;
use \App\Http\Controllers\AnswerController;
use \App\Http\Controllers\CommentController;
use \App\Http\Controllers\UserController;
use \App\Http\Controllers\SearchController;


Route::get('/', [HomeController::class, 'index'])->name('home');

Route::middleware(['auth'])->group(function () {
    Route::middleware(['xss'])->group(function () {
        Route::resource('questions', QuestionController::class)->except(['index', 'show']);
        Route::resource('questions.answers', AnswerController::class)->only(['store', 'update', 'destroy']);
        Route::resource('answers.comments', CommentController::class)->only(['store', 'update', 'destroy']);
        Route::post('questions/{question}/add-like', [QuestionController::class, 'addLike'])->name('questions.add.like');
        Route::post('answers/{answer}/add-like', [AnswerController::class, 'addLike'])->name('answers.add.like');
        Route::patch('users/{user}/profile', [UserController::class, 'update'])->name('users.profile.update');
        Route::get('users/{user}/profile/edit', [UserController::class, 'edit'])->name('users.profile.edit');
        Route::delete('users/{user}/profile', [UserController::class, 'destroy'])->name('users.profile.destroy');
    });
});

Route::get('questions/{question}', [QuestionController::class, 'show'])->name('questions.show');
Route::get('users/{user}/profile', [UserController::class, 'show'])->name('users.profile.show');
Route::get('search', SearchController::class)->name('search');

Auth::routes();
