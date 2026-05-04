<?php

namespace App\Http\Controllers;

use App\Models\Trimitere;
use App\Models\Pacient;
use App\Models\Programare;
use Illuminate\Http\Request;

class TrimitereController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // $trimiteri = Trimitere::with(['pacient', 'medic','programare'])
        // ->where('id_medic', auth()->id())
        // ->orderByDesc('data')
        // ->get();

        // return view('trimiteri.index', compact('trimiteri'));

    $query = Trimitere::query()->with(['pacient', 'medic'])->where('id_medic', auth()->id());;

    if ($request->filled('search') && $request->filled('search_field')) {
        $field = $request->search_field;
        if (in_array($field, ['titlu','locatie'])) {
            $query->where($field, 'like', '%' . $request->search . '%');
        }
    }

    if ($request->filled('data_emitere')) {
        $query->whereDate('data_emitere', $request->data_emitere);
    }
    if ($request->filled('data_expirare')) {
        $query->whereDate('data_expirare', $request->data_expirare);
    }

    if ($request->filled('litera')) {
        $litera = $request->litera;
        $query->where('titlu', 'like', $litera.'%');
    }

    if ($request->filled('sort_by') && in_array($request->sort_by, ['titlu','locatie','data_emitere','data_expirare'])) {
        $query->orderBy($request->sort_by, $request->direction ?? 'asc');
    }

    $limit = $request->get('limit', 10);
    $trimiteri = $query->paginate($limit);

    return view('trimiteri.index', compact('trimiteri'));         
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

        return view('trimiteri.create', compact('programari'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
            $request->validate([
            'id_programare' => 'required|exists:programari,id',
            'titlu' => 'required|string|max:20',
            'detalii' => 'nullable|string|max:100',
            'locatie' =>'required|string|max:50',
            'data_expirare' => 'required|date|after:now'
        
        ], [
                
            'id_programare.required'=> 'Alegerea unei programari este obligatorie.',
            'titlu.required' => 'Introducerea unui nume este obligatorie',
            'titlu.string' => 'Introduceti litere.',
            'titlu.max' => 'Numele nu poate contine mai mult de 20 de caractere.',
            'detalii.max' => 'Descrierea nu poate avea mai mult de 100 de caractere.',
            'locatie.required' => 'Locatia este obligatorie.',
            'locatie.max' => 'Locatie nu poate avea mai mult de 50 de caractere.',
            'data_expirare.required' => 'Introducerea datei este obligatorie.',
            'data_expirare.after' => 'Data trebuie să fie în viitor.',             

        ]);

        $programare = Programare::findOrFail($request->id_programare);

        Trimitere::create([
            'id_programare' => $programare->id,
            'id_pacient' => $programare->id_pacient,
            'id_medic' => $programare->id_medic,
            'titlu' => $request->titlu,
            'detalii' => $request->detalii,
            'data_emitere' => now(),
            'locatie' => $request->locatie,
            'data_expirare'=>$request->data_expirare,
        ]);

        return redirect()->route('trimiteri.index')
                         ->with('success','Trimitere adăugata cu succes.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Trimitere $trimitere)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $trimitere = Trimitere::with('programare.pacient')->findOrFail($id);

        $programari = Programare::with(['pacient','medic'])
            ->where('status','finalizata')
            ->where('id_medic', auth()->id())
            ->orderByDesc('data')
            ->get();

        return view('trimiteri.edit', compact('trimitere', 'programari'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
            $request->validate([
            'id_programare' => 'required|exists:programari,id',
            'titlu' => 'required|string|max:20',
            'detalii' => 'nullable|string|max:100',
            'locatie' =>'required|string|max:50',
            'data_expirare' => 'required|date|after:now'
        
        ], [
                
            'id_programare.required'=> 'Alegerea unei programari este obligatorie.',
            'titlu.required' => 'Introducerea unui nume este obligatorie',
            'titlu.string' => 'Introduceti litere.',
            'titlu.max' => 'Numele nu poate contine mai mult de 20 de caractere.',
            'detalii.max' => 'Descrierea nu poate avea mai mult de 100 de caractere.',
            'locatie.required' => 'Locatia este obligatorie.',
            'locatie.max' => 'Locatie nu poate avea mai mult de 50 de caractere.',
            'data_expirare.required' => 'Introducerea datei este obligatorie.',
            'data_expirare.after' => 'Data trebuie să fie în viitor.',             

        ]);

        $trimitere = Trimitere::findOrFail($id);
        $programare = Programare::findOrFail($request->id_programare);
        // $diagnostic->update($request->only(['id_programare','id_pacient','nume', 'descriere']));
        $trimitere->update([
            'id_programare' => $programare->id,
            'id_pacient' => $programare->id_pacient,
            'id_medic' => $programare->id_medic,
            'titlu' => $request->titlu,
            'detalii' => $request->detalii,
            'locatie' => $request->locatie,
            'data_emitere' => now(),
            'data_expirare' => $request->data_expirare,
        ]);
        return redirect()->route('trimiteri.index')
                        ->with('success','Trimitere actualizata cu succes.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Trimitere $trimiteri)
    {
        $trimiteri->delete();
        return back()->with('success','Diagnostic sters');
    }
}
