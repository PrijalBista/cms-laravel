<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Traits\HasPhoto;

class Feed extends Model
{
	use HasPhoto;

    protected $fillable =  ['title', 'content'];

}
