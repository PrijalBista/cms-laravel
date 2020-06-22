<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Job;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class JobController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Job::all();
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
            'lastDate' => 'required|date',
            'offeredSalary' => 'required|string|max:255',
            'careerLevel' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'industry' => 'required|string|max:255',
            'experience' => 'required|string|max:255',
            'content' => 'required|string',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $newJob = Job::create(Arr::except($validatedData, 'images'));

        // Handle post photos upload
        if($request->has('images')) {
            $newJob->storeUploadedImages($request->images, 'job_images', 'Job');
        }

        return response()->json(null, 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Job  $job
     * @return \Illuminate\Http\Response
     */
    public function show(Job $job)
    {
        $job->photos;
        return $job;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Job  $job
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Job $job)
    {
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'lastDate' => 'required|date',
            'offeredSalary' => 'required|string|max:255',
            'careerLevel' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'industry' => 'required|string|max:255',
            'experience' => 'required|string|max:255',
            'content' => 'required|string',
            'items' => 'array',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);


        $job->update(Arr::except($validatedData,['items', 'images']));

        $job->deleteUploadedImagesExceptPassedImageNames($request->items);

        // If additional images are passed then insert them
        if($request->has('images')) {
            $job->storeUploadedImages($request->images, 'job_images', 'Job');
        }

        return response()->json(null, 200);   
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Job  $job
     * @return \Illuminate\Http\Response
     */
    public function destroy(Job $job)
    {
        $job->delete();
        return response()->json(null, 200);
    }
}
