<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Requests\UserRequest;

class UserController extends Controller
{
    public function index(){
        try {
            $users = User::with('blogs')->get();
            return response()->json([
                'success' => true,
                'message' => 'user list successfully',
                'data' => $users,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'something went wrong',
                'error' => config('app.debug') ? $e->getMessage() : 'Something went wrong',
            ], 500);
        }
    }
    public function show($id){
        try {
            $user = User::with('blogs')->findOrFail($id);
            return response()->json([
                'success' => true,
                'message' => 'user successfully',
                'data' => $user,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'something went wrong',
                'error' => config('app.debug') ? $e->getMessage() : 'Something went wrong',
            ], 500);
        }
    }
    public function update(UserRequest $request){}
}
