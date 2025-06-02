<?php

namespace App\Policies;

use App\Models\Course;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CoursePolicy
{
    use HandlesAuthorization;

    public function view(User $user, Course $course): bool
    {
        return $user->courses()->where('course_id', $course->id)->exists();
    }

    public function create(User $user): bool
    {
        return $user->system_admin;
    }

    public function update(User $user, Course $course): bool
    {
        return $this->view($user, $course) && (($role = $user->courses()->find($course)->pivot->role) == "teacher" || $role == "teaching_assistant");
    }

    public function delete(User $user, Course $course): bool
    {
        return $this->view($user, $course) && ($user->courses()->find($course)->pivot->role == "teacher"|| $user->system_admin);
    }
}
