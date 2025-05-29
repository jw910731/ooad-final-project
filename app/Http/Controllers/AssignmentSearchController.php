<?php

namespace App\Http\Controllers;

use App\Models\Assignment;
use App\Models\Course;
use App\Models\User;
use Illuminate\Http\Request;

class AssignmentSearchController extends Controller
{
    public function search(Request $request)
    {
        $search = $request->query('search');
        $course_id = $request->query('course_id');
        $course = Course::find($course_id);
        if(empty($search) || $search == '') {
            return $course->assignments;
        }
        return $course->assignments()->whereLike('title', $search)->get();
    }
    public function index()
    {

    }

    public function create()
    {
    }

    public function store(Request $request)
    {
    }

    public function show($id)
    {
    }

    public function edit($id)
    {
    }

    public function update(Request $request, $id)
    {
    }

    public function destroy($id)
    {
    }
}
