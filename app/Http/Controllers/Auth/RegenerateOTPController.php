<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\RegenerateOTPResource;
use App\Models\User;
use App\Models\Otp;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use App\Events\ResendOTPRegisteredEvent;

class RegenerateOTPController extends Controller
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
            'email' => ['required'],
        ],
        [
            'email.required' => 'Masukan Email yang akan dikirimkan OTP'
        ]
        );
        
        if ($validator->fails()) {
            $response = [
                'response_code' => '01',
                'response_message' => $validator->messages()
            ];
            return response()->json($response, 200);
        }

        // Check user
        $user = User::where('email', $request->email)->first();
        if (empty($user)) {
            $response = [
                'response_code' => '01',
                'response_message' => 'User dengan Email tersebut tidak ditemukan'
            ];
            return response()->json($response, 200);
        }
        
        // validation ketersediaan otp 
        $otp = Otp::where('user_id', $user->id)->first();
        
        // Generate Otp akan dilakukan looping kalau otpnya nga uniq
        do {
            $otpcode = mt_rand(100000, 999999);
            $data = Otp::where('otp_code', $otpcode)->first();
        }
        while (!empty($data));
        
        $currentDateTime = Carbon::now();
        $newDateTime = $currentDateTime->addMinute(5);

        // Update OTP
        $otp->otp_code = $otpcode;
        $otp->valid_until = $newDateTime;
        $otp->updated_at = $currentDateTime;
        $otp->save();

        // Event Resend Email OTP
        event(new ResendOTPRegisteredEvent($user, $otp));

        return response()->json([
            'response_code' => '00',
            'response_message' => 'Generate OTP Berhasil',
            'data' => [
                "user" => new RegenerateOTPResource($user)
            ]
        ]);
    }
}
