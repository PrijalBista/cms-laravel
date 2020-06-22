<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Traits\HasPhoto;

class Feedback extends Model
{

	use HasPhoto;

    protected $fillable = ['title', 'content'];
}
