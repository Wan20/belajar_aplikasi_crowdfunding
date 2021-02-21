<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\UpdatePasswordResource;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;

class UpdatePasswordController extends Controller
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
                'password' => ['required', 'string', 'min:8'],
                'password_confirmation' => ['required', 'string', 'min:8'],
                'email' => ['required', 'string', 'email', 'max:255'],
            ],
            [
                'email.required' => 'Masukan Email Anda',
                'password.required' => 'Masukan Password Anda',
                'password.min' => 'Minimal Password sebanyak 8 karakter',
                'password_confirmation.required' => 'Masukan Password untuk konfirmasi kembali',
                'password_confirmation.min' => 'Minimal Password untuk konfirmasi sebanyak 8 karakter'
            ]
        );

        // check validation
        if ($validator->fails()) {
            $response = [
                'response_code' => '01',
                'response_message' => $validator->messages()
            ];
            return response()->json($response, 200);
        }

        if ($request->password !== $request->password_confirmation){
            $response = [
                'response_code' => '01',
                'response_message' => 'Password dan Konfirmasi Password tidak sama'
            ];
            return response()->json($response, 200);
        }

        // check user
        $user = User::where('email', $request->email)->first();
        if (empty($user)) {
            $response = [
                'response_code' => '01',
                'response_message' => 'User dengan Email tersebut tidak ditemukan'
            ];
            return response()->json($response, 200);
        }

        if (empty($user->email_verified_at)) {
            $response = [
                'response_code' => '01',
                'response_message' => 'Email tersebut belum terverifikasi, tidak dapat melakukan update password'
            ];
            return response()->json($response, 200);
        }

        // update user
        $user->password = Hash::make($request->password);
        $user->updated_at = Carbon::now();
        $user->save();
        
        return response()->json([
            'response_code' => '00',
            'response_message' => 'Password berhasil di ubah',
            'data' => [
                "user" => new UpdatePasswordResource($user)
            ]
        ]);
    }
}
