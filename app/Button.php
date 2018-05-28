<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Button extends Model
{
    protected $fillable =
        [
            'infrared_id',
            'code_id',
            'x',
            'y'
        ];

    public function infrared()
    {
        return $this->hasOne(Infrared::class, 'id', 'infrared_id');
    }

    public function code(){
        return $this->hasOne(Code::class, 'id', 'code_id');
    }
}
