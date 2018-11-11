<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Middleware extends Model
{
    protected $fillable = ['name', 'logic', 'routes'];

    protected $casts = ['routes' => 'array'];

}
