<?php

use App\Http\Controllers\CourseController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\StudentController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});
Route::get('/login', [LoginController::class, 'login'])->name('login');
Route::post('/login', [LoginController::class, 'loginForm'])->name('login.form');
Route::middleware(['authLog', 'checkrole:admin'])->group(function () {
    Route::get('/admin/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');
    Route::resource('students', StudentController::class);
    Route::get('/admin/profile', [DashboardController::class, 'adminProfile'])->name('admin.profile');
    Route::post('profile', [DashboardController::class, 'updateAdminProfile'])->name('update.admin.profile');
    Route::post('change-password', [DashboardController::class, 'changePassword'])->name('change.password');
    Route::get('/admin/courses', [CourseController::class, 'allCourses'])->name('admin.courses');
});

// Routes accessible only by users with the 'admin' role
Route::middleware(['authLog', 'checkrole:student'])->group(function () {
    Route::get('/student/dashboard', [DashboardController::class, 'studentDashboard'])->name('student.dashboard');
    Route::get('/student/profile', [DashboardController::class, 'profile'])->name('student.profile');
    Route::post('profile/student', [DashboardController::class, 'updateStudentProfile'])->name('students.profile.update');
    Route::get('/all-courses', [CourseController::class, 'displayCourses'])->name('student.course');
    Route::post('change-password', [DashboardController::class, 'changePassword'])->name('change.password');
    Route::post('student/courses', [CourseController::class, 'activateStudentCourse'])->name('student.courses');

});

Route::post('/logout', [LoginController::class, 'logout'])->name('logout.user');
