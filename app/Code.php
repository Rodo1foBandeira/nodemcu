<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Code extends Model
{
    protected $fillable =
        [
            'device_id',
            'name',
            'code'
        ];

    public function device()
    {
        return $this->hasOne(Device::class, 'id', 'device_id');
    }

    public function buttons()
    {
        return $this->hasMany(Button::class);
    }
}
