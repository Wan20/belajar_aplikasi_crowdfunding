<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Traits\Uuids;
use App\Role;

class User extends Authenticatable
{
    use Notifiable, Uuids;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'username', 'email', 'phone_no', 'password', 'role_id'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function isEmailVerified(){
        if($this->email_verified_at) {
            return true;
        }
        return false;
    }
    
    public function isAdmin(){
        $isAdmin = Role::where('id', $this->role_id)->first()->role_name;
        if($isAdmin === 'admin') {
            return true;
        }
        return false;
    }
}
