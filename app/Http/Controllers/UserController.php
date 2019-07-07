<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index() {
        $users = User::all();
        return response()->json([
            'status' => 'success',
            'users' => $users->toArray()
        ], 200);
    }

    public function show(Request $request, $id) {
        $user = User::find($id);
        return response()->json([
            'status' => 'success',
            'user' => $user->toArray()
        ], 200);
    }

    public function create(Request $request) {
        // this method is post
        $user = User::create($request->all());
    }

    public function update(Request $request, $id) {
        $user = User::find($id);
    }

    public function delete(Request $request, $id) {
        User::find($id)->delete();
        return response(['is_success' => true], 200);
    }

    public function edit(Request $request, $id) {
        
    }
}
