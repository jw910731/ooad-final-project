<?php

use App\Http\Controllers\AssignmentSearchController;
use App\Http\Controllers\UserSearchController;
use App\Models\Course;
use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

Route::get('/', function () {
    if (! auth()->check()) {
        return redirect()->route('login');
    }

    return redirect()->route('courses.index');
})->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Volt::route('courses', 'courses.index')->name('courses.index');
    Volt::route('courses/create', 'courses.create')->can('create', Course::class)->name('courses.create');
    Volt::route('courses/{course}', 'courses.show')->name('courses.show');
    Volt::route('courses/{course}/add_info', 'courses.add_info')->name('courses.add_info');
    Volt::route('courses/{course}/assignment', 'assignments.index')->name('assignment.index');
    Volt::route('courses/{course}/assignment/create', 'assignments.create')->name('assignment.create');
    Volt::route('courses/{course}/assignment/{assignment}', 'assignments.show')->name('assignment.show');
    Volt::route('courses/{course}/assignment/{assignment}/edit', 'assignments.edit')->name('assignment.edit');
    Volt::route('courses/{course}/score', 'scores.index')->name('score.index');
    Volt::route('courses/{course}/score/create', 'scores.create')->name('score.create');
    Volt::route('courses/{course}/score/{score}', 'scores.show')->name('score.show');
    Route::get('api/user_search/search', [UserSearchController::class, 'search'])->name('userSearch.search');
    Route::get('api/user_search/searchTeacher', [UserSearchController::class, 'searchTeacher'])->name('userSearchTeacher.search');
    Route::get('api/assignment_search/search', [AssignmentSearchController::class, 'search'])->name('assignmentSearch.search');
    Volt::route('courses/{course}/member', 'members.index')->name('member.index');
    Volt::route('courses/{course}/member/add', 'members.add')->name('member.add');
    Volt::route('courses/{course}/member/{member}', 'members.show')->name('member.show');
    Volt::route('courses/{course}/member/{member}/update', 'members.update')->name('member.update');
});

Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Volt::route('settings/profile', 'settings.profile')->name('settings.profile');
    Volt::route('settings/password', 'settings.password')->name('settings.password');
    Volt::route('settings/appearance', 'settings.appearance')->name('settings.appearance');

});

require __DIR__.'/auth.php';