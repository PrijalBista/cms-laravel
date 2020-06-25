<?php

namespace App\Http\Controllers\Api\CMS;

use App\Http\Controllers\Controller;
use App\Photo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CarouselController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Photo::where('category', 'Carousel')->get();
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
            'tags' => 'string|max:255',
        ]);

        // store photo

        foreach($request->images as $image) {

            $title = $image->getClientOriginalName();

            $title = $this->getImageTitle($title);

            $url = $image->store('carousel', 'public');

            // Add record
            $data['title'] = isset($validatedData['title']) ? $validatedData['title'] : $title;
            $data['url'] = $url;
            $data['category'] = 'Carousel';
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
        // block user to show photos that are of category other than Carousel
        if($photo->category !== 'Carousel') abort(404);
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

        // block user to update photos that are of category other than Carousel
        if($photo->category !== 'Carousel') abort(404);

        $validatedData = $request->validate([
            'title' => 'string|max:255',
            'image' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
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
        // block user to delete photos that are of category other than Carousel
        if($photo->category !== 'Carousel') abort(404);
        $photo->delete();
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
