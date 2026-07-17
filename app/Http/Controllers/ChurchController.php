<?php

namespace App\Http\Controllers;

use App\Models\Church;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ChurchController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $churches = Church::all();
        if(request()->is('api/*')){
            return response()->json(['churches'=>$churches]);
        }
        return view('church.index', compact('churches'));
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
            'name' => 'required|unique:churches,name',
            'address' => 'required',
            'phone' => 'required',
            'email' => 'required|email|unique:churches,email',
            'website' => 'required|url',
            'location'=>'required'
        ]);
        if(!$validated){
            return redirect()->back()->withErrors($validated)->withInput();
        }
        Church::create($validated);
        if (request()->is('api/*')) {
            return response()->json(['message' => 'Church added successfully'],201);
        }
        return back()->with('success', 'Church added successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(Church $church)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Church $church)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Church $church)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Church $church)
    {
        //
    }
}
