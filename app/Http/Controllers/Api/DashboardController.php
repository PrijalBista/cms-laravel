<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Email;
use App\Post;
use App\Feed;
use App\Photo;
use App\Job;

class DashboardController extends Controller
{

	public function getCounts() {

		return response()->json([
			'emails' => Email::count(),
			'posts' => Post::count(),
			'feeds' => Feed::count(),
			'photos' => Photo::count(),
			'jobs' => Job::count(),
		], 200);
	}



	public function paginatedPosts() {
		return Post::paginate(6);
	}

	public function paginatedFeeds() {
		return Feed::paginate(6);
	}

}
