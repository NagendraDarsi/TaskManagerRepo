<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use App\Http\Controllers\AuthController;
use App\Http\Controllers\TaskController;

Route::get('login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('login', [AuthController::class, 'login']);

// Admin Login
Route::get('/admin/login', [AuthController::class, 'showLoginForm'])->name('admin.login');
Route::post('/admin/login', [AuthController::class, 'Adminlogin'])->name('admin.login');

Route::get('register', [AuthController::class, 'showRegistrationForm'])->name('register');
Route::get('/app', [AuthController::class, 'showDashboard'])->name('app')->middleware('auth');
//Route::get('/app', [TaskController::class, 'getTaskInfo'])->name('app')->middleware('auth');
Route::get('/fetch-tasks', [AuthController::class, 'fetchTasksByStatus'])->name('fetch.tasks')->middleware('auth');
//usercreatio
Route::post('register', [AuthController::class, 'register']);
Route::get('/usercreation', [AuthController::class, 'ShowUserCreation'])->name('usercreation');
Route::group(['middleware' => ['auth', 'ADMIN']], function () {
    Route::post('/usercreation', [AuthController::class, 'usercreation']);
});

//Route::get('/taskcreation', [TaskController::class, 'taskcreation'])->name('taskcreation');

Route::group(['middleware' => ['auth', 'ADMIN']], function () {
    //Route::get('/taskcreation', [TaskController::class, 'getusers'])->name('taskcreation.getusers');
    Route::post('/taskcreation', [TaskController::class, 'store'])->name('taskcreation.store');
});

Route::post('logout', [AuthController::class, 'logout'])->name('logout');
Route::get('dashboard', function () {
    return view('auth.dashboard');
});
Route::get('/', function () {
    return view('welcome');
});

// get method tasks
Route::get('/tasks', [TaskController::class, 'index'])->name('tasks.index');
//put method for tasks
Route::get('/tasks/{id}', [TaskController::class, 'editeachtask'])->name('tasks.editeachtask');
// Route::middleware('auth')->group(function () {
Route::post('/tasks/{id}', [TaskController::class, 'update'])->name('tasks.update');
// });
Route::delete('/tasks/{id}', [TaskController::class, 'destroy'])->name('tasks.destroy');
Route::get('/fetch-users', [AuthController::class, 'fetchUsers']);
// In routes/api.php
Route::get('/users/{userId}', [AuthController::class, 'showUserforEdit']);
Route::post('/users/{userId}', [AuthController::class, 'UsersforEdit']);
Route::delete('/users/{userId}', [AuthController::class, 'destroy']);

