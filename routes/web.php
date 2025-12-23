<?php

use App\Http\Controllers\CourseController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\StudentController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});
Route::middleware('login')->group(function () {
    Route::get('/login', [LoginController::class, 'login'])->name('login');
    Route::post('/login', [LoginController::class, 'loginform'])->name('loginform');
});
Route::middleware('auth')->group(function () {
    Route::get('/admin-dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');
    Route::resource('students', StudentController::class);
    Route::get('/student-dashboard', [DashboardController::class, 'studentdashboard'])->name('student.dashboard');
    Route::get('/profile', [DashboardController::class, 'profile'])->name('student.profile');
    Route::get('/admin-profile', [DashboardController::class, 'adminProfile'])->name('admin.profile');
    Route::post('profile-student', [DashboardController::class, 'updateStudentProfile'])->name('students.profile.update');
    Route::post('profile', [DashboardController::class, 'updateAdminProfile'])->name('update.admin.profile');

    Route::post('changepassword', [DashboardController::class, 'changePassword'])->name('change.password');
    Route::get('/all-courses', [CourseController::class, 'displaycourses'])->name('student.course');
    Route::get('/admin-courses', [CourseController::class, 'allCourses'])->name('admin.courses');
    Route::post('student-courses', [CourseController::class, 'activateStudentCourse'])->name('student.courses');

});
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
