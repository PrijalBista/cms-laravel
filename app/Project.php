<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Traits\HasPhoto;

class Project extends Model
{
	use HasPhoto;

    protected $fillable = ['title', 'content'];
}
