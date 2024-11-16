<?php

namespace App\Http\Controllers;

use App\Http\Requests\BlogRequest;
use App\Models\Blog;
use Error;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use PhpParser\Node\Stmt\TryCatch;
use Symfony\Component\HttpKernel\Event\ResponseEvent;

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
    public function upload(Request $request)
    {
        try {

            $validator = Validator::make(request()->all(), [
                'photo' => 'required|image'
            ]);

            if ($validator->fails()) {
                $flatMapErrors = collect($validator->errors())->flatMap(function ($e, $field) {
                    return [$field => $e[0]];
                });

                return response()->json([
                    'message' => $flatMapErrors,
                    'status' => 422
                ], 422);
            }

            $path = '/storage/'.request('photo')->store('/blog_img');

            return [
                'path' => $path,
                'status' => 200
            ];
        } catch (Exception $e) {
            return response()->json([
                'errors' => $e->getMessage(),
                'status' => 500
            ], 500);
        };
    }


    /**
     * Update the specified resource in storage.
     */
    public function update($id)
    {
        try {
            //select the current blog
            $blog = Blog::find($id);

            //if there is no blog return some stuff
            if (!$blog) {
                return response()->json([
                    'message' => 'There is no blogs',
                    'status' => 404
                ], 404);
            }

            //check the validation for update data
            $validator = Validator::make(request()->all(), [
                'title' => 'required',
                'description' => 'required',
                'photo' => 'required',

            ]);

            //if fail respone with error
            if ($validator->fails()) {
                $flatMapErrors = collect($validator->errors())->flatMap(function ($e, $field) {
                    return [$field => $e[0]];
                });

                return response()->json([
                    'errors' => $flatMapErrors,
                    'status' => 422
                ], 422);
            }

            //update the blog 
            $blog->title = request("title");
            $blog->description = request("description");
            $blog->photo = request("photo");

            $blog->save();

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
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            //check blog with such id is exit

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
