<?php

namespace App\Http\Controllers;

use App\Models\Diagnostic;
use App\Models\Pacient;
use App\Models\Programare;
use Illuminate\Http\Request;

class DiagnosticController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // $diagnostice = Diagnostic::with(['pacient', 'medic','programare'])
        // ->where('id_medic', auth()->id())
        // ->orderByDesc('data')
        // ->get();

        // return view('diagnostice.index', compact('diagnostice'));

    $query = Diagnostic::with(['pacient', 'medic','programare'])
        ->where('id_medic', auth()->id());


   if ($request->filled('nume')) {
        $query->where('nume', $request->nume);
    }

    if ($request->filled('data')) {
        $query->whereDate('data', $request->data);
    }

    if ($request->filled('litera')) {
        $litera = $request->litera;
        $query->where('nume', 'like', $litera.'%');
    }

  if ($request->filled('sort_by') && in_array($request->sort_by, ['nume','data'])) {
        $query->orderBy($request->sort_by, $request->direction ?? 'asc');
    }


    $limit = $request->get('limit', 10);
    $diagnostice = $query->paginate($limit);

    return view('diagnostice.index', compact('diagnostice'));               
    }
    

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $programari = Programare::with(['pacient','medic'])
            ->where('status','finalizata')
            ->where('id_medic', auth()->id())
            ->orderByDesc('data')
            ->get();

        return view('diagnostice.create', compact('programari'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'id_programare' => 'required|exists:programari,id',
            'nume' => 'required|string|max:20',
            'descriere' => 'nullable|string|max:100'], [
                
            'id_programare.required'=> 'Alegerea unei programari este obligatorie.',
            'nume.required' => 'Introducerea unui nume este obligatorie',
            'nume.string' => 'Introduceti litere.',
            'nume.max' => 'Numele nu poate contine mai mult de 20 de caractere.',
            'descriere.max' => 'Descrierea nu poate avea mai mult de 100 de caractere.'            
        ]);

        $programare = Programare::findOrFail($request->id_programare);

        Diagnostic::create([
            'id_programare' => $programare->id,
            'id_pacient' => $programare->id_pacient,
            'id_medic' => $programare->id_medic,
            'nume' => $request->nume,
            'descriere' => $request->descriere,
            'data' => now(),
        ]);

        return redirect()->route('diagnostice.index')
                         ->with('success','Diagnostic adăugat cu succes.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Diagnostic $diagnostic)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $diagnostic = Diagnostic::with('programare.pacient')->findOrFail($id);

        $programari = Programare::with(['pacient','medic'])
            ->where('status','finalizata')
            ->where('id_medic', auth()->id())
            ->orderByDesc('data')
            ->get();

        return view('diagnostice.edit', compact('diagnostic', 'programari'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'id_programare' => 'required|exists:programari,id',
            'nume' => 'required|string|max:20',
            'descriere' => 'nullable|string|max:100',
        ], [
            'nume.required' => 'Introducerea unui nume este obligatorie',
            'nume.string' => 'Introduceti litere.',
            'nume.max' => 'Numele nu poate contine mai mult de 20 de caractere.',
            'descriere.max' => 'Descrierea nu poate avea mai mult de 100 de caractere.'
        ]);

        $diagnostic = Diagnostic::findOrFail($id);
        $programare = Programare::findOrFail($request->id_programare);
        // $diagnostic->update($request->only(['id_programare','id_pacient','nume', 'descriere']));
        $diagnostic->update([
            'id_programare' => $programare->id,
            'id_pacient' => $programare->id_pacient,
            'id_medic' => $programare->id_medic,
            'nume' => $request->nume,
            'descriere' => $request->descriere,
            'data' => now(),
        ]);
        return redirect()->route('diagnostice.index')
                        ->with('success','Diagnostic actualizat cu succes.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Diagnostic $diagnostice)
    {
        $diagnostice->delete();
        return back()->with('success','Diagnostic sters');
    }
}
