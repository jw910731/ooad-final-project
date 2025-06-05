<?php

namespace App\Policies;

use App\Models\Assignment;
use App\Models\Course;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class AssignmentPolicy
{
    use HandlesAuthorization;

    public function view(User $user, Assignment $assignment): bool
    {
        return $user->courses()->where('course_id', $assignment->course_id)->exists() || $user->system_admin;
    }

    public function create(User $user, Course $course): bool
    {
        return $user->system_admin || ($user->can('update', $course));
    }

    public function update(User $user, Assignment $assignment): bool
    {
        return $user->system_admin || ($this->view($user, $assignment) && ($user->can('update', Course::find($assignment->course_id))));
    }

    public function delete(User $user, Assignment $assignment): bool
    {
        return $user->system_admin || ($this->view($user, $assignment) && ($user->can('update', Course::find($assignment->course_id))));
    }

    public function submit(User $user, Assignment $assignment): bool
    {
        return $user->system_admin || ($this->view($user, $assignment) && ( is_null($assignment->deadline)||now()->isBefore( $assignment->deadline))
                && ($user->courses()->find($assignment->course_id)->pivot->role == 'student'));
    }
}
