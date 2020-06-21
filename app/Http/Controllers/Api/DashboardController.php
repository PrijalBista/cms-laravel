<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Email;
use App\Post;
use App\Feed;
use App\Photo;

class DashboardController extends Controller
{
    
    public function getCounts() {

    	return response()->json([
    		'email' => Email::count(),
    		'posts' => Post::count(),
    		'feeds' => Feed::count(),
    		'photos' => Photo::count(),

    	], 200);
    }
}
