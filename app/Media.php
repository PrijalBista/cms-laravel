<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Media extends Model
{

    protected $fillable = ['title', 'file_name', 'mime_type', 'collection_name', 'url', 'mediaable_id', 'mediaable_type' ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = ['media_url'];


    // Setup polymorphic relationship allowing Feed, Post, etc (multiple models) to have photos.
    public function mediaable() {
    	return $this->morphTo(); // will get return single model of photoable_type having id photoable_id
    }

    public function getMediaUrlAttribute() {
    	return '/storage/' . $this->url;
    }

}
