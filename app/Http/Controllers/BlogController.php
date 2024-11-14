<?php

namespace App\Http\Controllers;

use App\Http\Requests\BlogRequest;
use App\Models\Blog;
use Error;
use Exception;
use Illuminate\Http\Request;
use PhpParser\Node\Stmt\TryCatch;

use function Laravel\Prompts\error;

class BlogController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            // take all blogs from database with pagination
            $blogs = Blog::with('category')->latest()->paginate();

            //if there is no blog return some stuff
            if (!$blogs) {
                return response()->json([
                    'message' => 'There is no blogs',
                    'status' => 404
                ], 404);
            }

            return [
                'status' => 200,
                'blogs' => $blogs
            ];
        } catch (Exception $e) {
            return response()->json([
                'errors' => $e->getMessage(),
                'status' => 500
            ], 500);
        };
    }

    /**
     * Store a newly created resource in storage.
     * @param title,description,photo,category_id,user_id
     */
    public function store(BlogRequest $request)
    {
        try {
            // checking all input are validate
            $cleanData = $request->validated();

            //create new blog with valid data
            $blog = Blog::create($cleanData);

            return [
                'status' => 200,
                'data' => $blog
            ];
        } catch (Exception $e) {
            return response()->json([
                'errors' => $e->getMessage(),
                'status' => 500
            ], 500);
        };
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            //check blog with such id  exit

            $blog = Blog::find($id);

            //if doesn't exit showing response error message
            if (!$blog) {
                return response()->json([
                    'status' => 404,
                    'message' => 'There have no blog with Id:' . $id
                ], 404);
            }

            //delete blog from database
            $blog->delete();

            return [
                'status' => 200,
                'message' => 'Remove Successfully'
            ];
        } catch (Exception $e) {
            return response()->json([
                'errors' => $e->getMessage(),
                'status' => 500
            ], 500);
        };
    }
}
