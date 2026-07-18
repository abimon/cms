<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Http\Controllers\Controller;
use App\Models\Church;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AccountController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (Auth::user()->role == 'Superadmin') {
            $accounts = Account::all();
        } else {
            $church = Church::where('name',Auth::user()->church)->first();
            $accounts = Account::where('church_id', $church->id)->get();
        }
        if (request()->is('api/*')) {
            return response()->json(['accounts' => $accounts]);
        }
        return view('account.index', compact('accounts'));
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
        $validate = request()->validate([
            'name'=>'required|string|max:30',
            'type'=>'required|in:public,private',
            'target'=>'nullable|integer',
            'church_id'=>'required|in:churches.id',
            'status'=>'nullable|in:active,inactive'
        ]);
        if(!$validate){
            return response()->json(['error' => 'Validation failed'], 400);
        }
        $account = Account::create([
            'name'=>request('name'),
            'parent_id'=>request('parent_id')??null,
            'type'=>request('type'),
            'target'=>request('target'),
            'status'=>request('status')??'active',
            'created_by'=>Auth::user()->id,
            'church_id'=>request('church_id'),
        ]);
        if(request()->is('api/*')){
            return response()->json(['message' => 'Account created successfully','account'=>$account], 201);
        }
        return redirect()->back()->with('success', 'Account created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(Account $account)
    {
        //
    }
    

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Account $account)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update($id)
    {
        $account = Account::findOrFail($id);
        if(request('name')!=null){
            $account->name = request('name');
        }
        if(request('parent_id')!=null){
            $account->parent_id = request('parent_id');
        }
        if(request('type')!=null){
            $account->type = request('type');
        }
        if(request('target')!=null){
            $account->target = request('target');
        }
        if(request('status')!=null){
            $account->status = request('status');
        }
        if(request('created_by')!=null){
            $account->created_by = request('created_by');
        }
        if(request('church_id')!=null){
            $account->church_id = request('church_id');
        }
        $account->update();
        if(request()->is('api/*')){
            return response()->json(['message' => 'Account updated successfully','account'=>$account], 200);
        }
        return redirect()->back()->with('success', 'Account updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Account $account)
    {
        //
    }
}
