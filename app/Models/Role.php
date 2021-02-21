<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuids;

class Role extends Model
{
    use Uuids;
    protected $guarded = [];
}
