<?php

namespace App\Http\Controllers\Api;

use App\Photo;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;

class PhotoController extends Controller
{
	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index()
	{
		$categories = Photo::distinct()->whereNotNull('category')->select('category')->get();

		$category_array = [];

		foreach($categories as $category) {
			array_push($category_array, $category->category);
		}

		return [
			'photos' => Photo::all(),
			'categories' => $category_array,
		];
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function store(Request $request)
	{

		$validatedData = $request->validate([
			'title' => 'string|max:255',
			'images.*' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
			// 'category' => 'string|max:255',
			'tags' => 'string|max:255',
		]);

		// store photo

		foreach($request->images as $image) {

			$title = $image->getClientOriginalName();

			$title = $this->getImageTitle($title);

			$url = $image->store('gallery', 'public');

			// Add record
			$data['title'] = isset($validatedData['title']) ? $validatedData['title'] : $title;
			$data['url'] = $url;
			// $data['category'] = isset($validatedData['category']) ? $validatedData['category'] : 'Gallery';
			$data['category'] = 'Gallery';
			$data['tags'] = isset($validatedData['tags']) ? $validatedData['tags'] : '';

			Photo::create($data);
		}


		return response()->json(null, 200);
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  \App\Photo  $photo
	 * @return \Illuminate\Http\Response
	 */
	public function show(Photo $photo)
	{
		return $photo;
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \App\Photo  $photo
	 * @return \Illuminate\Http\Response
	 */
	public function update(Request $request, Photo $photo)
	{
		$validatedData = $request->validate([
			'title' => 'string|max:255',
			'image' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
			// 'category' => 'string|max:255',
			'tags' => 'string|max:255',
		]);

		if($request->has('image')) {
			// Delete previous photo
			$prev_file = $photo->url;

			$new_file = $request->image->store('gallery', 'public');

			Storage::disk('public')->delete($prev_file);

			$photo->url = $new_file;
		}

		if(isset($validatedData['title'])) {
			$photo->title = $validatedData['title'];
		}

		// if(isset($validatedData['category'])) {
		// 	$photo->category = $validatedData['category'];
		// }

		if(isset($validatedData['tags'])) {
			$photo->tags = $validatedData['tags'];
		}


		$photo->save(); // update

        return response()->json(null, 200);
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  \App\Photo  $photo
	 * @return \Illuminate\Http\Response
	 */
	public function destroy(Photo $photo)
	{
		$photo->delete();
		return response()->json(null, 200);
	}



	/*
	 * Method to get title from users original file name
	 * Copy of App\Traits\HasPhoto getImageTitle method
	 */
	protected function getImageTitle($originalNameWithExtension) {
		// make title unique using time() and rand()
		$filename = pathinfo($originalNameWithExtension, PATHINFO_FILENAME);
		$filename = (strlen($filename) > 10) ? substr($filename, 0, 10) : $filename; // only take 10 chars from filename
		$extension = pathinfo($originalNameWithExtension, PATHINFO_EXTENSION);
		return $filename . time() . rand(10, 999) . '.' . $extension;
	}
}
