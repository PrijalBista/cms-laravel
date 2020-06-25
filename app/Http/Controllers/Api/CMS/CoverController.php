<?php

namespace App\Http\Controllers\Api\CMS;

use App\Http\Controllers\Controller;
use App\Photo;
use Illuminate\Http\Request;

class CoverController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Photo::where('category', 'Cover')->get();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Photo  $photo
     * @return \Illuminate\Http\Response
     */
    public function show(Photo $photo)
    {
        if($photo->category !== 'Cover') abort(404);
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
        
        // block user to update photos that are of category other than Cover
        if($photo->category !== 'Cover') abort(404);

        $validatedData = $request->validate([
            'image' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if($request->has('image')) {

            $prev_file = $photo->url;

            $new_file = $request->image->store('covers', 'public');

            if($prev_file != 'covers/default-cover-1920x1080.jpg') { // donot delete the default image
                Storage::disk('public')->delete($prev_file);
            }

            $photo->url = $new_file;
        }

        $photo->save(); // update

        return response()->json(null, 200);
    }
}
