<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    public function index()
    {
        $blogs = Blog::orderBy('date', 'desc')->get()->map(function ($blog) {
            return [
                'id' => $blog->id,
                'title' => $blog->title,
                'summary' => $blog->summary,
                'content' => $blog->content,
                'date' => $blog->date,
                'image' => $blog->image_url, // Full URL for frontend
            ];
        });

        return response()->json([
            'status' => 'success',
            'data' => $blogs
        ], 200);
    }

    public function show($id)
    {
        $blog = Blog::find($id);

        if (!$blog) {
            return response()->json([
                'status' => 'error',
                'message' => 'Blog not found'
            ], 404);
        }

        $blogData = [
            'id' => $blog->id,
            'title' => $blog->title,
            'summary' => $blog->summary,
            'content' => $blog->content,
            'date' => $blog->date,
            'image' => $blog->image_url
        ];

        return response()->json([
            'status' => 'success',
            'data' => $blogData
        ], 200);
    }
}
