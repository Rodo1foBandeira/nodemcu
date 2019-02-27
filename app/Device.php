<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Device extends Model
{
    protected $fillable =
        [
            'name',
            'type',
            'bits'
        ];

    public function codes()
    {
        return $this->hasMany(Code::class);
    }
}
