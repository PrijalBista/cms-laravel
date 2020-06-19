<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use App\Post;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Post::with('photos')->get();
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
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $newPost = Post::create(Arr::except($validatedData, 'images'));

        // Handle post photos upload
        if($request->has('images')) {
            $this->storeUploadedImages($newPost, $request->images, 'post_images');
        }

        return response()->json(null, 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Post $post)
    {
        $post->photos;
        return $post;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Post $post)
    {

        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'items' => 'array|present',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $post->update(Arr::except($validatedData, ['images', 'items']));

        // Handle post photos upload/update
        // delete images if deleted
        if($request->has('items')) {
            $this->deleteUploadedImages($post, $request->items);
        }
        // If additional images are there then insert them
        if($request->has('images')) {
            $this->storeUploadedImages($post, $request->images, 'post_images');
        }

        return response()->json(null, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Post $post)
    {
        $post->photos()->delete();
        $post->delete();

        return response()->json(null, 200);
    }

    protected function storeUploadedImages($post, $images, $folderName = 'images') {

        foreach ($images as $image) {

            $title = $image->getClientOriginalName();

            // make title unique using time() and rand()
            $filename = pathinfo($title, PATHINFO_FILENAME);
            $filename = (strlen($filename) > 10) ? substr($filename, 0, 10) : $filename; // only take 10chars from filename
            $extension = pathinfo($title, PATHINFO_EXTENSION);
            $title =  $filename . time() . rand(10, 999) . '.' . $extension;

            $url = $image->store($folderName, 'public');

            // Add new record in photos
            $post->photos()->create([
                'title' => $title,
                'url' => $url,
            ]);
        }

        return true;
    }

    protected function deleteUploadedImages($post, $items) {

        $post->photos()->whereNotIn('title', $items)->delete();
        return true;
    }

    // protected function updateUploadedImages($post, $newImages, $folderName = 'images') {
        
    //     // TODO : optimize this method to only delete phots which are updated.
    //     // This can be done by first matching the upload file name and photo->name
    //     // Then extension (jpg, png) , size matching.

    //     // delete all photos uploaded previously
    //     $post->photos()->delete();

    //     // reupload all new photos 
    //     return $this->storeUploadedImages($post, $newImages, $folderName);
    // }

}
