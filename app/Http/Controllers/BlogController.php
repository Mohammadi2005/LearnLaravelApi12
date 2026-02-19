<?php

namespace App\Http\Controllers;

use App\Http\Resources\BlogResource;
use App\Models\Blog;
use App\Http\Requests\BlogRequest;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    public function index(){
        try {
            $blog = Blog::all();
            return response()->json([
                'success' => true,
                'message' => 'blog list successfully',
                'data' => $blog,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'blog list not found',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }
    public function show($slug){
        try {
            $blog = Blog::where('slug', $slug)->firstOrFail();
            return response()->json([
                'success' => true,
                'message' => 'blog show successfully',
                'data' => $blog,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'blog show not found',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }

    public function store(BlogRequest $request){
        try {

            $blog = Blog::create($request->validated());

            return response()->json([
                'success' => true,
                'message' => 'blog created successfully',
                'data' => $blog,
            ], 201);

        } catch (\Exception $e) {

            return response()->json([
                'success' => false,
                'message' => 'خطا در ایجاد بلاگ',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);

        }
    }
}
