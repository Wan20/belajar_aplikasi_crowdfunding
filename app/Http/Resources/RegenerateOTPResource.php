<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class RegenerateOTPResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'name' => $this->username,
            'email' => $this->email,
            'email_verified_at' => null,
            'photo' => null,
            'created_at' => $this->created_at->format("Y-m-d H:i:s"),
            'updated_at' => $this->updated_at->format("Y-m-d H:i:s"),
            'id' => $this->id,
        ];
    }
}
