<?php

namespace App\Http\Controllers;

use App\Models\Poster;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PosterController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(['posters'=>Poster::where('church_id',request('church_id'))], 200);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = request()->validate([
            'name' => 'required|string|max:30',
            'description' => 'required|string|max:200',
            'church_id' => 'required|exists:churches,id',
            'category' => 'required|string',
            'status' => 'nullable|in:active,inactive',
        ]);
        if (!$validated) {
            return response()->json(['message' => 'Validation failed', 'errors' => $validated], 400);
        }
        if (request()->hasFile('image')) {
            $validated['image_path'] = request()->file('image')->store('images', 'public');
        } else {
            return response()->json(['message' => 'Image is required'], 400);
        }
        Poster::create($validated);
        return response()->json(['message' => 'Poster created successfully'], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Poster $poster)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Poster $poster)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update($id)
    {
        $poster = Poster::findOrFail($id);
        if(request('name')!= null){
            $poster->name = request('name');
        }
        if(request('description')!=null){
            $poster->description=request('description');
        }
        if (request()->hasFile('image')) {
            $poster->image_path = request()->file('image')->store('images', 'public');
        }
        if(request('church_id')!=null){
            $poster->church_id=request('church_id');
        }
        if(request('category')!=null){
            $poster->category=request('category');
        }
        if(request('status')!=null){
            $poster->status=request('status');
        }
        $poster->update();
        return response()->json(['message' => 'Poster updated successfully'], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        Poster::destroy($id);
        return response()->json(['message' => 'Poster deleted successfully'], 200);
    }
}
