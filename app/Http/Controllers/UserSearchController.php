<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserSearchController extends Controller
{
    public function search(Request $request)
    {
        $search = $request->query('search');
        $excludeCourse_id = $request->query('excludeCourse_id');

        $users = User::toBase();
        if (! is_null($excludeCourse_id)) {
            $users = User::whereDoesntHave('courses', function ($q) use ($excludeCourse_id) {
                $q->where('course_id', $excludeCourse_id);
            });
        }
        if (empty($search) || $search == '') {
            return $users->get();
        }

        return $users->whereLike('name', $search)->get();
    }
    public function searchTeacher(Request $request)
    {
        $search = $request->query('search');
        $excludeStudent = $request->query('excludeStudent');

        $users = User::toBase();
        if(!is_null($excludeStudent)) {
            $users = User::whereHas('courses', function ($q) use ($excludeStudent) {
                $q->where('course_id', $excludeStudent)->where('role', 'teacher');
            });
        }

        if((empty($search))){
            return $users->get();
        }
        return $users->whereLike('name', $search)->get();
    }
    public function searchStudent(Request $request)
    {
        $search = $request->query('search');
        $excludeStudent = $request->query('excludeStudent');

        $users = User::toBase();
        if(!is_null($excludeStudent)) {
            $users = User::whereHas('courses', function ($q) use ($excludeStudent) {
                $q->where('course_id', $excludeStudent)->where('role', 'student');
            });
        }

        if((empty($search))){
            return $users->get();
        }
        return $users->whereLike('name', $search)->get();
    }
}
