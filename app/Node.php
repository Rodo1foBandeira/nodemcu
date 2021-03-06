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
    
    public static function boot() {
        parent::boot();

        static::deleting(function($node) { // before delete() method call this
             $node->ports()->delete();
             // do the rest of the cleanup...
        });
    }
}
