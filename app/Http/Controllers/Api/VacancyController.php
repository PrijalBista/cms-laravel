<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Vacancy;
use Illuminate\Http\Request;

class VacancyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Vacancy::all();
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
        ]);

        Vacancy::create($validatedData);

        return response()->json(null, 200);

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Vacancy  $vacancy
     * @return \Illuminate\Http\Response
     */
    public function show(Vacancy $vacancy)
    {
        return $vacancy;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Vacancy  $vacancy
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Vacancy $vacancy)
    {
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'complete' => 'boolean',
        ]);

        $vacancy->update($validatedData);

        return response()->json(null, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Vacancy  $vacancy
     * @return \Illuminate\Http\Response
     */
    public function destroy(Vacancy $vacancy)
    {
        $vacancy->delete();
        return response()->json(null, 200);
    }
}
