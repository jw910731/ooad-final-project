<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserSearchController extends Controller
{
    public function search(Request $request)
    {
        $search = $request->query('search');
        if(empty($search) || $search == '') {
            return User::all();
        }
        return User::whereLike('name', $search)->get();
    }
}
