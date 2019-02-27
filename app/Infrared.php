<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Infrared extends Model
{
    protected $fillable =
        [
            'port_id',
            'name',
            'lines',
            'columns'
        ];

    public function port()
    {
        return $this->hasOne(Port::class, 'id', 'port_id');
    }

    public function buttons()
    {
        return $this->hasMany(Button::class);
    }
}
