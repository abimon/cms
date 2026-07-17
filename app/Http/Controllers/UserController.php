<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Http\Controllers\Controller;
use App\Models\Church;
use App\Models\ChurchMember;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function login(){
        $validated = request()->validate([
            'email' => 'required',
            'password' => 'required',
        ]);
        if(!$validated){
            return response()->json(['message' => 'Validation failed'], 400);
        }
        try{
            $user = User::where('email', request('email'))->first();
            if(!$user || !Hash::check(request('password'), $user->password)){
                return response()->json(['message' => 'Invalid credentials'], 401);
            }
            return response()->json(['message' => 'User logged in successfully', 'user' => $user,'token'=>$user->createToken('auth_token')->plainTextToken], 200);
        }catch(\Exception $e){
            return response()->json(['message' => 'Error logging in user', 'error' => $e->getMessage()], 500);
        }
    }
    public function register(){
        $validated = request()->validate([
            'uid' => 'required|unique:users',
            'name' => 'required',
            'email' => 'required|unique:users',
            'phone' => 'required|unique:users',
            'wellbeing_status' => 'required',
            'role' => 'required',
            'password' => 'required',
            'church'=>'required|in:churches'
        ]);
        if(!$validated){
            return response()->json(['message' => 'Validation failed'], 400);
        }
        try{
            $user = User::create([
                'uid' => request('uid'),
                'name' => request('name'),
                'email' => request('email'),
                'phone' => request('phone'),
                'wellbeing_status' => request('wellbeing_status'),
                'role' => request('role'),
                'password' => Hash::make(request('password')),
            ]);
            $church = Church::where('name',request('church'))->first();
            ChurchMember::create([
                'member_id'=>$user->id,
                'church_id'=>$church->id
            ]);
            return response()->json(['message' => 'User created successfully', 'user' => $user,'token'=>$user->createToken('auth_token')->plainTextToken], 201);
        }catch(\Exception $e){
            return response()->json(['message' => 'Error creating user', 'error' => $e->getMessage()], 500);
        }
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        //
    }
}
