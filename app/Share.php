<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Traits\HasMedia;

class Share extends Model
{
	use HasMedia;

	protected $fillable = ['title', 'content'];

}
