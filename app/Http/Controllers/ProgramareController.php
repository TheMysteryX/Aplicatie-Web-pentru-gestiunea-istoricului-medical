<?php

namespace App\Http\Controllers;

use App\Models\Programare;
use App\Models\Pacient;
use App\Models\SolicitareProgramare;
use Illuminate\Http\Request;
use App\Mail\ProgramareAcceptataMail;
use Illuminate\Support\Facades\Mail;

class ProgramareController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
    $today = now();
    $limit = $request->get('limit', 10);

    $baseQuery = Programare::with('pacient')
        ->where('id_medic', auth()->id());

    if ($request->filled('nume')) {
        $baseQuery->whereHas('pacient', function ($q) use ($request) {
            $q->where('nume', 'like', '%'.$request->nume.'%')
              ->orWhere('prenume', 'like', '%'.$request->nume.'%');
        });
    }

    if ($request->filled('data')) {
        $baseQuery->whereDate('data', $request->data);
    }

    if ($request->filled('sort_by') && in_array($request->sort_by, ['nume','data'])) {
        if ($request->sort_by === 'nume') {
            $baseQuery->join('pacienti', 'pacienti.id', '=', 'programari.id_pacient')
                      ->orderBy('pacienti.nume', $request->direction ?? 'asc')
                      ->select('programari.*');
        } else {
            $baseQuery->orderBy('data', $request->direction ?? 'asc');
        }
    }

    $programariViitoare = (clone $baseQuery)
        ->where('status', 'viitoare')
        ->paginate($limit, ['*'], 'viitoare_page');

    $programariFinalizate = (clone $baseQuery)
        ->where('status', 'finalizata')
        ->paginate($limit, ['*'], 'finalizate_page');

    $programariAmanate = (clone $baseQuery)
        ->where('status', 'amanata')
        ->paginate($limit, ['*'], 'amanate_page');

    // pentru calendar (fără paginație)
    $programari = Programare::with('pacient')
        ->where('id_medic', auth()->id())
        ->get();

        return view('programari.index', compact('programari','programariViitoare','programariFinalizate','programariAmanate','programari'));
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
    // $pacienti = auth()->user()
    //     ->pacienti()
    //     ->orderBy('nume')
    //     ->get();

    // return view('programari.create', compact('pacienti'));

    $medic = auth()->user();

    $pacientSelectat = null;

    // luăm solicitarea direct din request (null dacă nu există)
    $solicitareId = $request->input('solicitare_id');

    // dacă vine din solicitare sau link cu pacient
    if ($request->pacient_id) {

        $pacient = Pacient::findOrFail($request->pacient_id);

        // dacă pacientul nu este deja în lista medicului
        if (!$medic->pacienti()->where('id_pacient', $pacient->id)->exists()) {
            $medic->pacienti()->attach($pacient->id);
        }

        $pacientSelectat = $pacient->id;
    }

    // lista pacienților medicului
    $pacienti = $medic->pacienti()->orderBy('nume')->get();

    return view('programari.create', compact(
        'pacienti',
        'pacientSelectat',
        'solicitareId'
    ));
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'id_pacient' => 'required|exists:pacienti,id',
            'data' => 'required|date|after:now',
            'detalii' => 'nullable|string|max:500',
        ], [
            'id_pacient.required' => 'Selectarea pacientului este obligatorie.',
            'data.required' => 'Introducerea datei este obligatorie.',
            'data.after' => 'Data trebuie să fie în viitor.',
        ]);

        $programare = Programare::create([
            'id_pacient' => $request->id_pacient,
            'id_medic' => auth()->id(),
            'data' => $request->data,
            'detalii' => $request->detalii,
        ]);

        if ($request->filled('solicitare_id')) {

            $solicitare = SolicitareProgramare::find($request->solicitare_id);

            if ($solicitare) {
                $solicitare->update([
                    'status' => 'rezolvata',
                    'programare_id' => $programare->id 
                ]);
                $user = $solicitare->pacient->user;
                Mail::to($user->email)->send(
                    new ProgramareAcceptataMail($programare)
                );
            }
        }        

        return redirect()->route('programari.index')->with('success', 'Programare adăugată cu succes.');
    }


    /**
     * Display the specified resource.
     */
    public function show(Programare $programare)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
    $programare = Programare::findOrFail($id);

    // nu putem edita programarea altui medic
    if ($programare->id_medic !== auth()->id()) {
        abort(403);
    }

    $pacienti = auth()->user()
        ->pacienti()
        ->orderBy('nume')
        ->get();

    return view('programari.edit', compact('programare', 'pacienti'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
    $programare = Programare::findOrFail($id);

    //dacă vine doar butonul de status rapid
    if ($request->has('status') && !$request->has('id_pacient')) {
        // validăm doar statusul
        $request->validate([
            'status' => 'required|in:viitoare,finalizata,amanata',
        ]);

        //nu putem finalizeza o programare din viitor
        if ($request->status === 'finalizata' && $programare->data->isFuture()) {
            return redirect()->back()->withErrors([
                'status' => 'Nu poți finaliza o programare care nu a avut loc încă.'
            ]);
        }

        $programare->update([
            'status' => $request->status,
        ]);

        return redirect()->route('programari.index')
            ->with('success', 'Statusul programării a fost actualizat.');
    }


    $request->validate([
        'id_pacient' => 'required|exists:pacienti,id',
        'data' => 'required|date',
        'status' => 'required|in:viitoare,finalizata,amanata',
        'detalii' => 'nullable|string|max:500',
    ], [
        'id_pacient.required' => 'Selectarea pacientului este obligatorie.',
        'data.required' => 'Introducerea datei este obligatorie.',
    ]);

    //daca programarea e in viitor nu o putem finaliza
    if ($request->status === 'finalizata' && \Carbon\Carbon::parse($request->data)->isFuture()) {
        return redirect()->back()->withErrors([
            'status' => 'Nu poti finaliza o programare care nu a avut loc inca.'
        ])->withInput();
    }

    //dacă programarea este viitoare, data trebuie să fie in viitor
    if ($request->status === 'viitoare' && \Carbon\Carbon::parse($request->data)->isPast()) {
        return redirect()->back()->withErrors([
            'data' => 'O programare marcată ca viitoare trebuie să fie în viitor.'
        ])->withInput();
    }

    $programare->update([
        'id_pacient' => $request->id_pacient,
        'id_medic'   => auth()->id(),
        'data'       => $request->data,
        'status'     => $request->status,
        'detalii'    => $request->detalii,
    ]);
    return redirect()->route('programari.index')->with('success','Programarea a fost modificată.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Programare $programari)
    {
        $programari->delete();
        return redirect()->route('programari.index')->with('success','Programare ștearsă');
    }
}
