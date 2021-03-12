<?php

namespace App\Http\Controllers\Profile;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\ProfileResource;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use App\Models\User;
use Carbon\Carbon;

class ProfileController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        return response()->json([
            'response_code' => '00',
            'response_message' => 'Profile Berhasil di tampilkan',
            'data' => [
                "profile" => new ProfileResource($request->user())
            ]
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        // validation
        $validator = Validator::make($request->all(), 
        [
            'image' => 'required|image:jpeg,png,jpg,gif,svg|max:2048',
            'email' => ['required', 'string', 'email', 'max:255'],
            'name' => ['string', 'min:3'],
        ],
        [
            'email.required' => 'Masukan Email Anda untuk melakukan update profile',
            'image.required' => 'Anda harus melakukan upload gambar dengan extensi berikut jpeg,png,jpg,gif,svg max:2048',
            'name.min' => 'Nama minimal 3 karakter',
        ]);

        // check validation
        if ($validator->fails()) {
            $response = [
                'response_code' => '01',
                'response_message' => $validator->messages()
            ];
            return response()->json($response, 200);
        }
        

        if($request->hasFile('image')) {
            $user = User::where('email', $request->email)->first();
            
            $image = $request->file('image');
            $image_extension = $image->getClientOriginalExtension();
            $image_name = $user->id . '.' . $image_extension;

            $image_folder = '/photos/users/photo-profile/';
            $image_location = $image_folder . $image_name;
            
            try {
                $image->move(public_path($image_folder), $image_name);

                if (!empty($request->name)) {
                    $user->username = $request->name;
                }
                $user->photo = $image_location;
                $user->updated_at = Carbon::now();
                $user->save();

            } catch (\Exception $e) {
                return response([
                    'response_code' => '01',
                    'response_message' => 'photo profile gagal upload',
                    'data' => [
                        "profile" => new ProfileResource($user)
                    ]
                ], 200);
            }
                  
            return response()->json([
                'response_code' => '00',
                'response_message' => 'Profile Berhasil di update',
                'data' => [
                    "profile" => new ProfileResource($user)
                ]
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
