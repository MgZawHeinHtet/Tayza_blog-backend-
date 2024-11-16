<?php

namespace App\Http\Controllers;

use App\Http\Requests\AuthFormRequest;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    // @need to implement a confirm password feature
    public function register(AuthFormRequest $request)
    {
        try {
            //checking validation for email,name,password
            $validator = $request->validated();

            //created user with correct validation
            $user = User::create($validator);

            //login if correct credentails
            Auth::attempt(['email' => $request->email, 'password' => $request->password]);

            // return token for signup user account
            $token = $user->createToken($user->id)->plainTextToken;

            return [
                'status' => 200,
                'message' => 'Create a account successfully🎉',
                'user' => auth()->user(),
                'token' => $token
            ];
        } catch (Exception $e) {
            return response()->json([
                'errors' => $e->getMessage(),
                'status' => 500
            ], 500);
        };
    }

    public function login(Request $request)
    {
        $validator = Validator::make(request()->all(),[
            'email' => 'required|email',
            'password' => 'required|min:9|string'
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


        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $user = auth()->user();
            $token = $user->createToken($user->id)->plainTextToken;
            return [
                'status' => 200,
                'message' => 'Login successfully 🎉',
                'user' => $user,
                'token' => $token,
            ];
        };

        return response()->json(['message' => 'Invalid credentials'], 401);
    }
}
