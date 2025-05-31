<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\User;
use Illuminate\Http\Request;

class UserSearchController extends Controller
{
    public function search(Request $request)
    {
        $search = $request->query('search');
        $excludeCourse_id = $request->query('excludeCourse_id');

        $users = User::toBase();
        if(!is_null($excludeCourse_id)) {
            $users = User::whereDoesntHave('courses', function ($q) use ($excludeCourse_id) {
                $q->where('course_id', $excludeCourse_id);
            });
        }
        if(empty($search) || $search == '') {
            return $users->get();
        }
        return $users->whereLike('name', $search)->get();
    }
}
