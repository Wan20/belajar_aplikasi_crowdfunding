<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\VerificationResource;
use App\Models\User;
use App\Models\Otp;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class VerificationController extends Controller
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
            'otp' => ['required', 'string', 'min:6', 'max:6'],
        ],
        [
            'otp.required' => 'Terdapat kesalahan pada OTP yang di kirimkan',
            'otp.min' => 'OTP yang di kirimkan kurang dari 6 digit',
            'otp.max' => 'OTP yang di kirimkan lebih dari 6 digit'
        ]
        );
        
        if ($validator->fails()) {
            $response = [
                'response_code' => '01',
                'response_message' => $validator->messages()
            ];
            return response()->json($response, 200);
        }

        // validation ketersediaan otp 
        $otp = Otp::where('otp_code', $request->otp)->first();
        
        if (empty($otp)) {
            $response = [
                'response_code' => '01',
                'response_message' => 'OTP Code yang Anda masukan salah'
            ];
            return response()->json($response, 200);
        }

        // validation waktu berlaku
        $currentDateTime = Carbon::now();

        if ($currentDateTime > $otp->valid_until) {
            $response = [
                'response_code' => '01',
                'response_message' => 'Kode Otp sudah tidak berlaku, silahkan generate ulang'
            ];
            return response()->json($response, 200);
        }

        // check user
        $user = User::where('id', $otp->user_id)->first();
        if (empty($user)) {
            $response = [
                'response_code' => '01',
                'response_message' => 'User dengan kode OTP tersebut tidak ditemukan'
            ];
            return response()->json($response, 200);
        }

        // update user
        $user->email_verified_at = Carbon::now();
        $user->updated_at = Carbon::now();
        $user->save();

        return response()->json([
            'response_code' => '00',
            'response_message' => 'Berhasil diverifikasi',
            'data' => [
                "user" => new VerificationResource($user)
            ]
        ]);
    }
}
