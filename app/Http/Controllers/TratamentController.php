<?php

namespace App\Http\Controllers;

use App\Models\Tratament;
use App\Models\Pacient;
use App\Models\Diagnostic;
use Illuminate\Http\Request;

class TratamentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
    $query = Tratament::with(['pacient', 'medic','diagnostic'])
        ->where('id_medic', auth()->id());

    if ($request->filled('search') && $request->filled('search_field')) {
        $field = $request->search_field;
        if ($field === 'nume') {

            $query->where('nume', 'like', '%' . $request->search . '%');
        } elseif ($field === 'diagnostic') {

            $query->whereHas('diagnostic', function($q) use ($request) {
                $q->where('nume', 'like', '%' . $request->search . '%');
            });
        }
    }
    
    if ($request->filled('data_inceput')) {
        $query->whereDate('data_inceput', $request->data_inceput);
    }
    if ($request->filled('data_sfarsit')) {
        $query->whereDate('data_sfarsit', $request->data_sfarsit);
    }

     if ($request->filled('litera')) {
        $litera = $request->litera;
        $query->where('nume', 'like', $litera.'%');
    }

    if ($request->filled('sort_by')) {
        $direction = $request->get('direction', 'asc');

        if (in_array($request->sort_by, ['data_inceput','data_sfarsit'])) {
            $query->orderBy($request->sort_by, $direction);

        } elseif ($request->sort_by === 'diagnostic') {
            $query->whereHas('diagnostic') ->orderBy(Diagnostic::select('nume')
                  ->whereColumn('diagnostice.id', 'tratamente.id_diagnostic'),$direction);
        }
    }

    $limit = $request->get('limit', 10);
    $tratamente = $query->paginate($limit);

    return view('tratamente.index', compact('tratamente'));  
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $diagnostice = Diagnostic::with(['pacient','medic'])
            ->where('id_medic', auth()->id())
            ->orderByDesc('data')
            ->get();

        return view('tratamente.create', compact('diagnostice'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
    $request->validate([
        'nume'=>'required|string|max:20',
        'instructiuni'=>'required|string',
        'id_diagnostic'=>'required|string|exists:diagnostice,id',
        'data_sfarsit'=>'required|date|after:data_inceput'],[

            'nume.required'=>'Numele tratamentului este obligatoriu.',
            'nume.max' => 'Numele tratamentului nu poate avea mai mult de 20 de caractere.',
            'instructiuni.required' => 'Introducerea instructiunilor este obligatorie.',
            'instructiuni.max' => 'Descrierea nu poate avea mai mult de 100 de caractere.',
            'id_diagnostic.required'=> 'Alegerea unui diagnostic este obligatoriu.',
            'data_sfarsit.required' => 'Introducerea datei este obligatorie.',
            'data_sfarsit.after' => 'Data trebuie să fie în viitor.',                           
        ]);

        $diagnostic = Diagnostic::findOrFail($request->id_diagnostic);

    Tratament::create([
        'id_diagnostic' => $diagnostic->id,
        'id_pacient'    => $diagnostic->id_pacient,
        'id_medic'      => $diagnostic->id_medic,        
        'nume'          => $request->nume,
        'instructiuni'  => $request->instructiuni,
        'data_inceput'  => now(),
        'data_sfarsit'  => $request->data_sfarsit
    ]);

        return redirect()->route('tratamente.index')
                         ->with('success','Tratament adăugat cu succes.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Tratament $tratament)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $tratament = Tratament::with('diagnostic.pacient')->findOrFail($id);

        $diagnostice = Diagnostic::with(['pacient','medic'])
            ->where('id_medic', auth()->id())
            ->orderByDesc('data')
            ->get();

        return view('tratamente.edit', compact('tratament', 'diagnostice'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
        'nume'=>'required|string|max:20',
        'instructiuni'=>'required|string',
        'id_diagnostic'=>'required|string|exists:diagnostice,id',
        'data_sfarsit'=>'required|date|after:data_inceput'],[

            'nume.required'=>'Numele tratamentului este obligatoriu.',
            'nume.max' => 'Numele tratamentului nu poate avea mai mult de 20 de caractere.',
            'instructiuni.required' => 'Introducerea instructiunilor este obligatorie.',
            'instructiuni.max' => 'Descrierea nu poate avea mai mult de 100 de caractere.',
            'id_diagnostic.required'=> 'Alegerea unui diagnostic este obligatoriu.',
            'data_sfarsit.required' => 'Introducerea datei este obligatorie.',
            'data_sfarsit.after' => 'Data trebuie să fie în viitor.',           
        ]);


        $tratament = Tratament::findOrFail($id);
        $diagnostic = Diagnostic::findOrFail($request->id_diagnostic);
        // $diagnostic->update($request->only(['id_programare','id_pacient','nume', 'descriere']));
        $tratament->update([
            'id_diagnostic' => $diagnostic->id,
            'id_pacient'    => $diagnostic->id_pacient,
            'id_medic'      => $diagnostic->id_medic,        
            'nume'          => $request->nume,
            'instructiuni'  => $request->instructiuni,
            'data_inceput'  => now(),
            'data_sfarsit'  => $request->data_sfarsit
        ]);
        return redirect()->route('tratamente.index')
                        ->with('success','Tratament actualizat cu succes.');        
    }
    

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Tratament $tratamente)
    {
        $tratamente->delete();
        return back()->with('success','Tratament sters');
    }
}
