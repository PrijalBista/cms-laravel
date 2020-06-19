<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $fillable = ['title', 'content'];

    // polymorphic relationship with App\Photo get photos related to Post Model and certain post id.
    public function photos() {
		return $this->morphMany('App\Photo', 'photoable');
    }
}
