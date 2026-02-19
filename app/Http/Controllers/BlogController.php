<?php

namespace App\Http\Controllers;

use App\Http\Resources\BlogResource;
use App\Models\Blog;
use App\Http\Requests\BlogRequest;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

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
            $validated = $request->validated();
            $file = $request->file('image');

            $originalName = $file->getClientOriginalName();
            $extension = $file->getClientOriginalExtension();

            $uniqName = $originalName . '-' . time() . '-' . Str::random(8) . '.' . $extension;

            $file->storeAs('blogsImage', $uniqName, 'public');

            $validated['image'] = $uniqName;

            $blog = Blog::create($validated);

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
    public function update(BlogRequest $request, Blog $blog){
        try {
            $blog->update($request->validated());
            Log::info('Blog updated successfully', [
                'blog_id' => $blog->id,
                'slug' => $blog->slug
            ]);
            return response()->json([
                'success' => true,
                'message' => 'blog updated successfully',
                'data' => new BlogResource($blog),
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'blog update not found',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }
    public function destroy(Blog $blog){
        try {
            $blog->delete();
            return response()->json([
                'success' => true,
                'message' => 'blog deleted successfully',
                'data' => $blog,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'blog deleted not found',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }
}
