<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Node extends Model
{
    protected $fillable =
        [
            'ip',
            'name'
        ];

    public function ports()
    {
        return $this->hasMany(Port::class);
    }
}
