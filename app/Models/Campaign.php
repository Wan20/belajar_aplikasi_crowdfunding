<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuids;

class Campaign extends Model
{
    use Uuids;

    protected $guarded = [];
}
