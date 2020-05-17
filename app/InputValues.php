<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class InputValues extends Model
{
    protected $fillable =
        [
            'input_id',
            'value'
        ];

    public function input()
    {
        return $this->hasOne(Input::class, 'id', 'input_id');
    }
}
