<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\LoginResource;
use Illuminate\Http\Exceptions\HttpResponseException;

class LoginController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        // validator
        $validator = Validator::make($request->all(), 
            [
                'password' => ['required', 'string', 'min:6'],
                'email' => ['required', 'string', 'email', 'max:255'],
            ],
            [
                'email.required' => 'Masukan Email Anda',
                'password.required' => 'Masukan Password Anda',
                'password.min' => 'Minimal Password sebanyak 6 karakter',
            ]
        );
        
        // check validation
        if ($validator->fails()) {

            // Get all the errors thrown
            $errors = collect($validator->errors());
            $msg = $errors->unique()->first();
            $response = [
                'response_code' => '01',
                'response_message' => $msg[0]
            ];
            return response()->json($response, 401);
        }

        if (!$token = auth()->attempt(request(['email', 'password']))) {
            $response = [
                'response_code' => '01',
                'response_message' => 'Email atau Password yang anda masukan tidak sesuai'
            ];
            return response()->json($response, 401);
        }
                
        if (empty(auth()->user()->email_verified_at)) {
            $response = [
                'response_code' => '01',
                'response_message' => 'Email tersebut belum terverifikasi, tidak dapat melakukan Login'
            ];
            return response()->json($response, 401);
        }

        $token = compact('token');
        return response()->json([
            'response_code' => '00',
            'response_message' => 'User Berhasil Login',
            'data' => [
                "token" => $token['token'],
                "user" => new LoginResource(auth()->user())
            ]
            ], 200);
    }
}
