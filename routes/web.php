<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Rota do painel de administração
Route::get('/admin/dashboard', function () {
    return view('admin.dashboard');
})->name('admin.dashboard');

// Rota do painel de professores
Route::get('/professor/dashboard', function () {
    return view('professor.dashboard');
})->name('professor.dashboard');
