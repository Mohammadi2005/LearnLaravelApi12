<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    public function index(){
        return response()->json([
           'data' => 'index',
        ]);
    }

    public function show($slug){
        return response()->json([
            'data' => 'show',
        ]);
    }

    public function store(Request $request){

        return response()->json([
            'data' => 'store',
        ]);
    }

    public function update(Request $request, $id){
        return response()->json([
            'data' => 'update',
        ]);
    }

    public function destroy($id){
        return response()->json([
            'data' => 'destroy',
        ]);
    }
}
