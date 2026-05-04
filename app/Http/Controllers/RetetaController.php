<?php

namespace App\Http\Controllers;

use App\Models\Reteta;
use App\Models\Pacient;
use App\Models\Diagnostic;
use Illuminate\Http\Request;

class RetetaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {

    $query = Reteta::with(['pacient', 'medic','diagnostic'])
        ->where('id_medic', auth()->id());

    if ($request->filled('diagnostic')) {
        $query->whereHas('diagnostic', function($q) use ($request) {
            $q->where('nume', 'like', '%' . $request->diagnostic . '%');
        });
    }

    if ($request->filled('data_emitere')) {
        $query->whereDate('data_emitere', $request->data_emitere);
    }
    if ($request->filled('data_expirare')) {
        $query->whereDate('data_expirare', $request->data_expirare);
    }

    if ($request->filled('litera')) {
        $litera = strtoupper($request->litera);
        $query->whereHas('diagnostic', function($q) use ($litera) {
            $q->where('nume', 'like', $litera.'%');
        });
    }

    if ($request->filled('sort_by')) {
        $direction = $request->get('direction', 'asc');
        if (in_array($request->sort_by, ['data_emitere','data_expirare'])) {
            $query->orderBy($request->sort_by, $direction);
        } elseif ($request->sort_by === 'diagnostic') {
            $query->join('diagnostice','retete.id_diagnostic','=','diagnostice.id')
                  ->orderBy('diagnostice.nume', $direction)
                  ->select('retete.*');
        }
    }

    $limit = $request->get('limit', 10);
    $retete = $query->paginate($limit);

    return view('retete.index', compact('retete'));        
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

        return view('retete.create', compact('diagnostice'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'id_diagnostic' => 'required|exists:diagnostice,id',
            'data_expirare' => 'required|date|after:now',
            'medicamente' => 'required|string|max:100'], [
                
            'id_diagnostic.required'=> 'Alegerea unui diagnostic este obligatoriu.',
            'data_expirare.required' => 'Introducerea datei este obligatorie.',
            'data_expirare.after' => 'Data trebuie să fie în viitor.',            
            'medicamente.max' => 'Descrierea nu poate avea mai mult de 100 de caractere.'            
        ]);

        $diagnostic = Diagnostic::findOrFail($request->id_diagnostic);

        Reteta::create([
            'id_diagnostic' => $diagnostic->id,
            'id_pacient' => $diagnostic->id_pacient,
            'id_medic' => $diagnostic->id_medic,
            'data' => $request->data,
            'data_emitere' => now(),
            'data_expirare' => $request->data_expirare,
            'medicamente' => $request->medicamente
        ]);

        return redirect()->route('retete.index')
                         ->with('success','Reteta adăugat cu succes.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Reteta $reteta)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $reteta = Reteta::with('diagnostic.pacient')->findOrFail($id);

        $diagnostice = Diagnostic::with(['pacient','medic'])
            ->where('id_medic', auth()->id())
            ->orderByDesc('data')
            ->get();

        return view('retete.edit', compact('reteta', 'diagnostice'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'id_diagnostic' => 'required|exists:diagnostice,id',
            'data_expirare' => 'required|date|after:now',
            'medicamente' => 'required|string|max:100'], [
                
            'id_diagnostic.required'=> 'Alegerea unui diagnostic este obligatoriu.',
            'data_expirare.required' => 'Introducerea datei este obligatorie.',
            'data_expirare.after' => 'Data trebuie să fie în viitor.',            
            'medicamente.max' => 'Descrierea nu poate avea mai mult de 100 de caractere.'            
        ]);


        $reteta = Reteta::findOrFail($id);
        $diagnostic = Diagnostic::findOrFail($request->id_diagnostic);
        // $diagnostic->update($request->only(['id_programare','id_pacient','nume', 'descriere']));
        $reteta->update([
            'id_diagnostic' => $diagnostic->id,
            'id_pacient' => $diagnostic->id_pacient,
            'id_medic' => $diagnostic->id_medic,
            'data' => $request->data,
            'data_emitere' => now(),
            'data_expirare' => $request->data_expirare,
            'medicamente' => $request->medicamente
        ]);
        return redirect()->route('retete.index')
                        ->with('success','Reteta actualizata cu succes.');        
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Reteta $retete)
    {
        $retete->delete();
        return back()->with('success','Reteta ștearsă');
    }
}
