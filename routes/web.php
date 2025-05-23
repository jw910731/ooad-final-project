<?php

use App\Http\Controllers\CourseController;
use App\Http\Controllers\UserSearchController;
use App\Http\Middleware\AdminMiddleware;
use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

Route::get('/', function () {
    if (!auth()->check()) {
        return redirect()->route('login');
    }
    return redirect()->route('courses.index');
})->name('home');

Route::middleware(['auth', 'verified', AdminMiddleware::class])->group(function () {
    Volt::route('courses/create', 'courses.create')->name('courses.create');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Volt::route('courses', 'courses.index')->name('courses.index');
    Volt::route('courses/{course}', 'courses.show')->name('courses.show');
    Volt::route('courses/{course}/assignment', 'assignment.index')->name('assignment.index');
    Volt::route('courses/{course}/assignment/{assignment}', 'assignment.show')->name('assignment.show');
    Volt::route('courses/{course}/score', 'score.index')->name('score.index');
    Volt::route('courses/{course}/score/{score}', 'score.show')->name('score.show');
    Route::get('api/user_search/search', [UserSearchController::class, 'search'])->name('userSearch.search');
});

Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Volt::route('settings/profile', 'settings.profile')->name('settings.profile');
    Volt::route('settings/password', 'settings.password')->name('settings.password');
    Volt::route('settings/appearance', 'settings.appearance')->name('settings.appearance');


});

require __DIR__ . '/auth.php';
