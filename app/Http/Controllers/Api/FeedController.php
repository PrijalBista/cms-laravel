<?php

namespace App\Http\Controllers\Api;

use App\Feed;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class FeedController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Feed::all();
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

        $newFeed = Feed::create(Arr::except($validatedData, 'images'));

        // Handle post photos upload
        if($request->has('images')) {
            $newFeed->storeUploadedImages($request->images, 'feed_images');
        }

        return response()->json(null, 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Feed  $feed
     * @return \Illuminate\Http\Response
     */
    public function show(Feed $feed)
    {
        $feed->photos;
        return $feed;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Feed  $feed
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Feed $feed)
    {
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'items' => 'array',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $feed->update(Arr::except($validatedData,['items', 'images']));

        // Handle feed photos upload/update
        // delete images if deleted
        // items is present if photos are present (always)
        // items can be absent when all photos are deleted i.e no photos left
        $feed->deleteUploadedImagesExceptPassedImageNames($request->items);

        // If additional images are passed then insert them
        if($request->has('images')) {
            $feed->storeUploadedImages($request->images, 'feed_images');
        }

        return response()->json(null, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Feed  $feed
     * @return \Illuminate\Http\Response
     */
    public function destroy(Feed $feed)
    {
        $feed->delete(); // all Photos related to $feed models will be automatically deleted because of the closure event listener added in Feed Model through HasPhoto trait.
        
        return response()->json(null, 200);
    }
}
