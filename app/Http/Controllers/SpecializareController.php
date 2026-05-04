<?php

namespace App\Http\Controllers;

use App\Models\Specializare;
use App\Models\User;

use Illuminate\Http\Request;

class SpecializareController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        return view('admin.dashboard', [
            'medici' => User::where('rol', 'medic'),
            'specializari' => Specializare::all(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('specializari.create', [
            'medici' => User::where('rol', 'medic'),
            'specializari' => Specializare::all(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate(['nume' => 'required|string|unique:specializari,nume',],
        ['nume.required' => 'Numele specializarii este obligatoriu.',
         'nume.unique' => 'Aceasta specializare exista deja',]);
        Specializare::create($request->only('nume'));
        return redirect()->route('admin.dashboard')->with('success', 'Specializare adaugata');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $specializare = Specializare::findOrFail($id); 
        return view('specializari.edit', compact('specializare'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate(['nume' => 'required|string|unique:specializari,nume'],['nume.required' => 'Numele specializarii este obligatoriu.',
        'nume.unique' => 'Aceasta specializare exista deja.']);
        $specializare = Specializare::findOrFail($id);
        $specializare->update($request->only('nume'));
        return redirect()->route('admin.dashboard')->with('success', 'Specializarea a fost actualizata.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $specializare = Specializare::findOrFail($id);
        $specializare->delete();
        return redirect()->route('admin.dashboard')->with('success', 'Specializarea a fost stearsa.');    
    }
}
