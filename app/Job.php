<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Traits\HasPhoto;

class Job extends Model
{

	use HasPhoto;

    protected $fillable = ['title', 'lastDate', 'offeredSalary', 'careerLevel', 'location', 'industry', 'experience', 'content' ];


}
