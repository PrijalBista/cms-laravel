<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Photo extends Model
{

    protected $fillable = ['title', 'url', 'photoable_id', 'photoable_type', 'category', 'tags', ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = ['photo_url'];


    // Setup polymorphic relationship allowing Feed, Post, etc (multiple models) to have photos.
    public function photoable() {
    	return $this->morphTo(); // will get return single model of photoable_type having id photoable_id   
    }


    public function getPhotoUrlAttribute() {
    	return '/storage/' . $this->url;
    }
}
