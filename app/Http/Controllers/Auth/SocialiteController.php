<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Role;
use App\Http\Resources\LoginResource;

class SocialiteController extends Controller
{
    public function redirectToProvider($provider)
    {
        //karena api.php pakai stateles bukan session
        $url = Socialite::driver($provider)->stateless()->redirect()->gettargetUrl();

        return response()->json([
            'url' => $url
        ]);
    }

    public function handleProviderCallback($provider)
    {
        try {
            $social_user = Socialite::driver($provider)->stateless()->user();

            // dd($social_user);
            if(!$social_user)
            {
                return response()->json([
                    'response_code' => '01',
                    'response_message' => 'Login Failed'
                ], 401);
            }

            $user = User::whereEmail($social_user->email)->first();

            if(!$user) {
                if($provider == 'google') {
                    $photo_profile = $social_user->avatar_original;
                }

                $role = Role::where('role_name', 'user')->first();

                $user = User::create([
                    'email' => $social_user->email,
                    'username' => $social_user->name,
                    'role_id' =>  $role->id,
                ]);

                $user->email_verified_at = Carbon::now();
                $user->photo = $photo_profile;
                $user->save();
            }

            return response()->json([
                'response_code' => '00',
                'response_message' => 'User Berhasil Login',
                'data' => [
                    "token" => auth()->login($user),
                    "user" => new LoginResource(auth()->user())
                ]
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'response_code' => '01',
                'response_message' => 'Login Failed ya'
            ], 401);
        }
    }
}
