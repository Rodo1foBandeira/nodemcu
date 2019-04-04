<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Port extends Model
{
    protected $fillable =
        [
            'pin',
            'name',
            'type',
            'group_id',
            'node_id'
        ];

    public function node()
    {
        return $this->hasOne(Node::class, 'id', 'node_id');
    }

    public function group()
    {
        return $this->hasOne(Group::class, 'id', 'group_id');
    }

    public function infrareds()
    {
        return $this->hasMany(Infrared::class);
    }
}
