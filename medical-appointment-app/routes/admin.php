<?php
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\PatientController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('admin.dashboard');
})->name('dashboard');

//Gestión de roles
Route::resource('roles', RoleController::class);
//Gestión de usuarios   
Route::resource('users', UserController::class);
//Gestión de pacientes
Route::resource('patients', PatientController::class);
//Gestión de doctores
Route::resource('doctors', \App\Http\Controllers\Admin\DoctorController::class);
Route::get('doctors/{doctor}/schedule', \App\Livewire\Admin\DoctorScheduleManager::class)->name('doctors.schedule');

// Gestión de Citas (Livewire)
Route::get('appointments', \App\Livewire\Admin\AppointmentIndex::class)->name('appointments.index');
Route::get('appointments/create', \App\Livewire\Admin\AppointmentCreate::class)->name('appointments.create');
Route::get('appointments/{appointment}/consultation', \App\Livewire\Admin\ConsultationManager::class)->name('appointments.consultation');
