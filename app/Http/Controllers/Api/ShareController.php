<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Share;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use App\Media;
use Illuminate\Support\Facades\Storage;

class ShareController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Share::all();
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
            'images.*' => 'mimes:jpeg,png,jpg,gif,zip,pdf|max:5120'
        ]);

        $newShare = Share::create(Arr::except($validatedData, 'images'));

        // Handle share media upload
        if($request->has('images')) {
            $newShare->storeUploadedMedias($request->images, 'media_images');
        }

        return response()->json(null, 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Share  $share
     * @return \Illuminate\Http\Response
     */
    public function show(Share $share)
    {
        $share->medias;
        return $share;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Share  $share
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Share $share)
    {
        
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'items' => 'array',
            'images.*' => 'mimes:jpeg,png,jpg,gif,zip,pdf|max:5120'
        ]);

        $share->update(Arr::except($validatedData, ['images', 'items']));

        $share->deleteUploadedMediasExceptPassedMediaNames($request->items);

        // If additional images are passed then insert them
        if($request->has('images')) {
            $share->storeUploadedMedias($request->images, 'media_images');
        }

        return response()->json(null, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Share  $share
     * @return \Illuminate\Http\Response
     */
    public function destroy(Share $share)
    {
        $share->delete();
        return response()->json(null, 200);
    }

 /**
  * Remove the specified resource from storage.
  *
  * @param  \App\Media  $media
  * @return \Illuminate\Http\Response Download
  */   
    public function downloadMedia(Media $media) {
        return Storage::disk('public')->download($media->url);
    }
}
