<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Input extends Model
{
    protected $fillable =
        [
            'node_id',
            'group_id',
            'name',
            'enabled',
        ];

    public function node()
    {
        return $this->hasOne(Node::class, 'id', 'node_id');
    }

    public function group()
    {
        return $this->hasOne(Group::class, 'id', 'group_id');
    }
}
