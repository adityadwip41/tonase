<?php

namespace App\Http\Controllers;


use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Validator;
use App\User;

class AuthController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string|min:6'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        if (!$token = auth()->attempt($validator->validated())) {
            return response()->json([
                'error_email' => 'Data yang anda masukan salah',
                'error_password' => 'Data yang anda masukan salah'
            ], 401);
        }

        return $this->createNewToken($token);
    }

    /**
     * Register a User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|between:2,100',
            'email' => 'required|string|email|max:100|unique:users',
            'password' => 'required|string|confirmed|min:7',
        ]);

        if ($validator->fails()) {
            $message = $validator->messages();

            return response()->json([
                'status'=> 400,
                'message' => $message,
                'error' =>$validator->errors()
            ]);
        }

        try {
            $user = User::create(array_merge($validator->validated(),['password' => bcrypt($request->password)]));

            return response()->json([
                'status'=> 200,
                'message' => 'Registrasi Akun Berhasil',
            ], 200);

        } catch(\Exception $e) {
            return redirect()->back()->with([
                'status'=> 400,
                'message' => "Registrasi Gagal",
                'error' => $e->getMessage()
            ]);
        }
    }


    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth()->logout();

        return response()->json(['message' => 'User successfully signed out']);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->createNewToken(auth()->refresh());
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function userProfile()
    {
        $user = User::find( auth()->user()->id );
        return response()->json([
            'message' => 'Successfully..',
            'user' => $user
        ]);
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function createNewToken($token)
    {
        $user = User::find(auth()->user()->id);

        return response()->json([
            'status' => 200,
            'message' => 'Successfully..',
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60,
        ]);
    }

    public function changePassword(Request $request)
    {
        //
    }

}
