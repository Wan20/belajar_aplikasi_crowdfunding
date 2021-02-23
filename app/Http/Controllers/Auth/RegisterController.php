<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\RegisterResource;
use App\Models\User;
use App\Models\Role;
use App\Models\Otp;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use App\Events\UserRegisteredEvent;

class RegisterController extends Controller
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
            'name' => ['required', 'string', 'min:3', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            ],
            [
                'name.required' => 'Masukan Nama Anda',
                'name.min' => 'Nama minimal 3 karakter',
                'email.required' => 'Masukan Email yang akan menjadi akun Anda',
                'email.unique' => 'Email Anda sudah terdaftar sebelumnya, silahkan menggunakan email lain atau langsung melakukan Login'
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

        $role = Role::where('role_name', 'user')->first();

        // Register user
        $user = User::create([
            'username' => request('name'),
            'email' => request('email'),
            'role_id' =>  $role->id,
        ]);

        // Generate Otp akan dilakukan looping kalau otpnya nga uniq
        do {
            $otpcode = mt_rand(100000, 999999);
            $data = Otp::where('otp_code', $otpcode)->first();
        }
        while (!empty($data));
        $currentDateTime = Carbon::now();
        $newDateTime = $currentDateTime->addMinute(5);;

        // Create OTP
        $otp = Otp::create([
            'otp_code' => $otpcode,
            'valid_until' => $newDateTime,
            'user_id' =>  $user->id,
            'is_active' => true
        ]);

        // Event Kirim Email OTP
        event(new UserRegisteredEvent($user, $otp));
        
        return response()->json([
            'response_code' => '00',
            'response_message' => 'silahkan cek email',
            'data' => [
                "user" => new RegisterResource($user)
            ]
        ]);
    }
}
