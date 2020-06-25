<?php

namespace App\Http\Controllers\Api;

use App\Project;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class ProjectController extends Controller
{
	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index()
	{
		return Project::all();
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
			'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            'complete' => 'boolean',
		]);

		$newProject = Project::create(Arr::except($validatedData, 'images'));

		// Handle post photos upload
		if($request->has('images')) {
			$newProject->storeUploadedImages($request->images, 'project_images', 'Project');
		}

		return response()->json(null, 200);
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  \App\Project  $project
	 * @return \Illuminate\Http\Response
	 */
	public function show(Project $project)
	{
		$project->photos;
		return $project;
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \App\Project  $project
	 * @return \Illuminate\Http\Response
	 */
	public function update(Request $request, Project $project)
	{

		$validatedData = $request->validate([
			'title' => 'required|string|max:255',
			'content' => 'required|string',
			'items' => 'array',
			'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            'complete' => 'boolean',
		]);

		$project->update(Arr::except($validatedData, ['images', 'items']));

		$project->deleteUploadedImagesExceptPassedImageNames($request->items);

		// If additional images are passed then insert them
		if($request->has('images')) {
			$project->storeUploadedImages($request->images, 'project_images', 'Project');
		}

		return response()->json(null, 200);
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  \App\Project  $project
	 * @return \Illuminate\Http\Response
	 */
	public function destroy(Project $project)
	{
		$project->delete();
		return response()->json(null, 200);
	}
}
