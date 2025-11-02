<?php

use App\Livewire\BusScheduleUi;
use App\Livewire\AdminBusSchedule;
use Illuminate\Support\Facades\Route;

// Public UI - Main Home Page
Route::get('/', BusScheduleUi::class)->name('schedule.public'); 

// Admin CRUD - Secured management page
// NOTE: For a real project, wrap this in an auth middleware!
Route::get('/admin', AdminBusSchedule::class)->name('schedule.admin');
