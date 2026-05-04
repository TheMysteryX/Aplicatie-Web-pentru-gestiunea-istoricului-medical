<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Pacient;
use App\Models\Specializare;
use App\Models\Programare;
use App\Models\Reteta;
use App\Models\Diagnostic;  
use App\Models\Tratament;
use App\Models\Trimitere;
use App\Models\SolicitareProgramare;

class PacientController extends Controller
{

public function dashboard(Request $request) {
    $pacient = auth()->user()->pacient;
    $notificari = SolicitareProgramare::where('pacient_id', $pacient->id)->whereIn('status', ['rezolvata', 'respinsa'])->latest()->get();

    // PROGRAMARI
    $query = Programare::where('id_pacient', $pacient->id);

    if ($request->filled('data')) {
        $query->whereDate('data', $request->data);
    }

    if ($request->filled('sort_by') && $request->sort_by === 'data') {
        $query->orderBy('data', $request->direction ?? 'asc');
    }

    $programari = $query->paginate($request->get('limit', 10));


    // RETETE
    $query = Reteta::with(['medic','diagnostic'])
        ->where('id_pacient', $pacient->id);

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

    $retete = $query->paginate($request->get('limit', 10));


    // DIAGNOSTICE
    $query = Diagnostic::with(['medic'])
        ->where('id_pacient', $pacient->id);

    if ($request->filled('nume')) {
        $query->where('nume', 'like', '%' . $request->nume . '%');
    }

    if ($request->filled('data')) {
        $query->whereDate('data', $request->data);
    }

    if ($request->filled('sort_by') && in_array($request->sort_by, ['nume','data'])) {
        $query->orderBy($request->sort_by, $request->direction ?? 'asc');
    }

    $diagnostice = $query->paginate($request->get('limit', 10));


    // TRATAMENTE
    $query = Tratament::with(['medic','diagnostic'])
        ->where('id_pacient', $pacient->id);

    if ($request->filled('search') && $request->filled('search_field')) {
        if ($request->search_field === 'nume') {
            $query->where('nume', 'like', '%' . $request->search . '%');

        } elseif ($request->search_field === 'diagnostic') {
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

    $tratamente = $query->paginate($request->get('limit', 10));


    // TRIMITERI
    $query = Trimitere::where('id_pacient', $pacient->id);

    if ($request->filled('search') && $request->filled('search_field')) {
        if (in_array($request->search_field, ['titlu','locatie'])) {
            $query->where($request->search_field, 'like', '%' . $request->search . '%');
        }
    }

    if ($request->filled('data_emitere')) {
        $query->whereDate('data_emitere', $request->data_emitere);
    }

    if ($request->filled('data_expirare')) {
        $query->whereDate('data_expirare', $request->data_expirare);
    }

    $trimiteri = $query->paginate($request->get('limit', 10));


    // MEDICI
    $query = User::where('rol', 'medic')
        ->whereIn('id', function($q) use ($pacient) {
            $q->select('id_medic')
            ->from('programari')
            ->where('id_pacient', $pacient->id);
        })
        ->with('specializare');

    if ($request->filled('search') && $request->filled('search_field')) {
        $field = $request->search_field;

        if (in_array($field, ['nume', 'prenume', 'cnp'])) {
            $query->where($field, 'like', '%' . $request->search . '%');

        } elseif ($field === 'spec') {
            $query->whereHas('specializare', function($q) use ($request) {
                $q->where('nume', 'like', '%' . $request->search . '%');
            });
        }
    }

    if ($request->filled('litera')) {
        $litera = strtoupper($request->litera);
        $query->where('nume', 'like', $litera . '%');
    }


    if ($request->filled('sort_by')) {
        $direction = $request->get('direction', 'asc');

        if (in_array($request->sort_by, ['nume', 'prenume', 'cnp'])) {
            $query->orderBy($request->sort_by, $direction);

        } elseif ($request->sort_by === 'spec') {
            $query->join('specializari', 'users.id_specializare', '=', 'specializari.id')
                ->orderBy('specializari.nume', $direction)
                ->select('users.*');
        }
    }

    $medici = $query->paginate($request->get('limit', 10));
    
    $mediciFeatured = User::where('rol', 'medic')
    ->with('specializare')
    ->inRandomOrder()
    ->limit(5)
    ->get();

    return view('pacienti.dashboard', compact(
        'pacient',
        'programari',
        'retete',
        'diagnostice',
        'tratamente',
        'trimiteri',
        'medici',
        'mediciFeatured',
        'notificari'
    ));
}

public function stergeNotificare($id)
{
    $pacient = auth()->user()->pacient;

    $notif = SolicitareProgramare::where('id', $id)
        ->where('pacient_id', $pacient->id)
        ->firstOrFail();

    $notif->delete();

    return back()->with('success', 'Notificare ștearsă.');
}

public function index(Request $request) {
        // $pacienti = Pacient::orderBy('nume')->get();
        // return view('pacienti.index', compact('pacienti'));
    
 $query = Pacient::query();

    if (auth()->user()->isMedic()) {
        $query->whereHas('medici', function ($q) {
            $q->where('id_medic', auth()->id());
        });
    }

    if ($request->filled('search') && $request->filled('search_field')) {
        $field = $request->search_field;
        if (in_array($field, ['nume','prenume','cnp'])) {
            $query->where($field, 'like', '%' . $request->search . '%');
        }
    }

    if ($request->filled('litera')) {
        $litera = $request->litera;
        $query->where('nume', 'like', $litera.'%');
    }

    if ($request->filled('sort_by') && in_array($request->sort_by, ['nume','prenume','data_nasterii'])) {
        $query->orderBy($request->sort_by, $request->direction ?? 'asc');
    }

    $limit = $request->get('limit', 10);
    $pacienti = $query->paginate($limit);

    $pacientiDisponibili = collect();

    if (auth()->user()->isMedic()) {
        $pacientiDisponibili = Pacient::whereDoesntHave('medici', function ($q) {
            $q->where('id_medic', auth()->id());
        })->orderBy('nume')->limit(20)->get();
    }

    return view('pacienti.index', compact('pacienti', 'pacientiDisponibili'));        
    }

    public function create() { return view('pacienti.create'); }

    public function store(Request $request) {
        $request->validate([
            'nume'=>'required|string|max:100',
            'prenume'=>'required|string|max:100',
            'data_nasterii'=>'required|date',
            'cnp'=>'required|regex:/^[0-9]+$/|max:20',
            'telefon'=>'required|regex:/^[0-9]+$/|max:20',
            'adresa' => 'nullable|string|max:100',
            'asigurat/a'=>'required|boolean'],[

            'nume.required' => 'Numele medicului este obligatoriu.',
            'nume.string' => 'Introduceti litere.',
            'nume.max' => 'Numele nu poate contine mai mult de 20 de caractere.',

            'prenume.required' => 'Prenumele medicului este obligatoriu.',
            'prenume.string' => 'Introduceti litere.',
            'prenume.max' => 'Prenumele nu poate contine mai mult de 20 de caractere.',

            'data_nasterii.required' => 'Introducerea adresei de e-mail este obligatorie.',

            'cnp.required' => 'Introducerea CNP-ului este obligatorie.',
            'cnp.regex' => 'Introduceti cifre.',
            'cnp.max' => 'Numele nu poate contine mai mult de 20 de caractere.',
            
            'adresa.required' => 'Numele medicului este obligatoriu.',
            'adresa.max' => 'Adresa nu poate contine mai mult de 50 de caractere.',

            'telefon.required' => 'Introducerea numarului de telefon este obligatorie.',
            'telefon.regex' => 'Introduceti cifre.',
            'telefon.max' => 'Numarul de telefon nu poate contine mai mult de 20 de caractere.',    

            'asigurat/a.required' => 'Selectarea asigurarii este obligatorie.'
        ]);
        
        $pacient = Pacient::create($request->all());

        if (auth()->user()->isMedic()) {
            auth()->user()->pacienti()->attach($pacient->id);
        }        
        return redirect()->route('pacienti.index', $pacient)->with('success','Pacient creat');
    }

    // public function show(Pacient $pacienti) {
    //     $pacienti->load(['programari.medic','retete.medic','diagnostice.medic','tratamente.medic', 'trimiteri.medic']);
    //     return view('pacienti.show', compact('pacienti'));
    // }

    public function createExistent(Request $request)
{
    $medic = auth()->user();

    if (!$medic->isMedic()) {
        abort(403);
    }

    $query = Pacient::whereDoesntHave('medici', function ($q) use ($medic) {
        $q->where('id_medic', $medic->id);
    });

    if ($request->filled('search')) {
        $query->where(function ($q) use ($request) {
            $q->where('nume', 'like', '%'.$request->search.'%')
              ->orWhere('prenume', 'like', '%'.$request->search.'%')
              ->orWhere('cnp', 'like', '%'.$request->search.'%');
        });
    }

    $pacienti = $query->orderBy('nume')
                      ->paginate(10)
                      ->withQueryString();

    return view('pacienti.existent', compact('pacienti'));
}

public function storeExistent(Pacient $pacient)
{
    $medic = auth()->user();

    if (!$medic->isMedic()) {
        abort(403);
    }

    if (!$medic->pacienti()->where('id_pacient', $pacient->id)->exists()) {
        $medic->pacienti()->attach($pacient->id);
    }

    return redirect()->route('pacienti.index')
        ->with('success', 'Pacient adăugat în lista ta.');
}



public function show(Request $request, Pacient $pacienti) {
    // PROGRAMARI
    $query = Programare::with('pacient')
        ->where('id_medic', auth()->id())
        ->where('id_pacient', $pacienti->id);

    if ($request->filled('data')) {
        $query->whereDate('data', $request->data);
    }

    if ($request->filled('sort_by') && in_array($request->sort_by, ['data'])) {
            $query->orderBy('data', $request->direction ?? 'asc');
        }
    $limit = $request->get('limit', 10);
    $programari = $query->paginate($limit);


    // RETETE
    $query = Reteta::with(['pacient', 'medic','diagnostic'])
        ->where('id_medic', auth()->id())
        ->where('id_pacient', $pacienti->id);

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


    // DIAGNOSTICE
    $query = Diagnostic::with(['pacient', 'medic','programare'])
        ->where('id_medic', auth()->id())
        ->where('id_pacient', $pacienti->id);


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


    // TRATAMENTE
    $query = Tratament::with(['pacient', 'medic','diagnostic'])
        ->where('id_medic', auth()->id())
        ->where('id_pacient', $pacienti->id);

    if ($request->filled('search') && $request->filled('search_field')) {
        $field = $request->search_field;
        if ($field === 'nume') {
            // caută după numele tratamentului
            $query->where('nume', 'like', '%' . $request->search . '%');
        } elseif ($field === 'diagnostic') {
            // caută după numele diagnosticului (relație)
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


    // TRIMITERI
    $query = Trimitere::query()->where('id_medic', auth()->id())
        ->where('id_pacient', $pacienti->id);

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

    return view('pacienti.show', compact(
        'pacienti', 'programari', 'retete', 'diagnostice', 'tratamente', 'trimiteri'
    ));
}

public function istoric(Request $request, Pacient $pacient) {
    
    #MEDICI
    $query = $pacient->medici()->with('specializare');
    if ($request->filled('search') && $request->filled('search_field')) {
        $field = $request->search_field;
        if (in_array($field, ['nume','prenume','cnp'])) {
            $query->where($field, 'like', '%' . $request->search . '%');
        }
        if ($field == 'spec') {
            $query->whereHas('specializare', function($q) use ($request) {
                $q->where('nume', 'like', '%' . $request->search . '%');
            });
        }
    }
    if ($request->filled('litera')) {
        $query->where('nume', 'like', $request->litera.'%');
        }
    if ($request->filled('sort_by')) {
        $direction = $request->get('direction', 'asc');

    if (in_array($request->sort_by, ['nume','prenume', 'cnp'])) {
            $query->orderBy($request->sort_by, $direction);

        } elseif ($request->sort_by === 'spec') {
            $query->orderBy(Specializare::select('nume')->whereColumn('specializari.id', 'users.spec_id'),$direction);
        }
    }
    $mediciLimit = $request->get('limit', 10);
    $medici = $query->paginate($mediciLimit)->withQueryString();

    #PROGRAMARI
    $query = Programare::with('pacient')
        ->where('id_pacient', $pacient->id);

    if ($request->filled('data')) {
        $query->whereDate('data', $request->data);
    }

    if ($request->filled('sort_by') && in_array($request->sort_by, ['data'])) {
            $query->orderBy('data', $request->direction ?? 'asc');
        }
    $limit = $request->get('limit', 10);
    $programari = $query->paginate($limit);        


    #RETETE
    $query = Reteta::with(['pacient', 'medic','diagnostic'])
        ->where('id_pacient', $pacient->id);

    if ($request->filled('medic')) {
        $query->whereHas('medic', function ($q) use ($request) {
            $q->where('nume', 'like', '%'.$request->medic.'%')
            ->orWhere('prenume', 'like', '%'.$request->medic.'%');
            });
        }  

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


    #DIAGNOSTICE
    $query = Diagnostic::with(['pacient', 'medic','programare'])
        ->where('id_pacient', $pacient->id);


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


    #TRATAMENTE
    $query = Tratament::with(['pacient', 'medic','diagnostic'])
        ->where('id_pacient', $pacient->id);

    if ($request->filled('search') && $request->filled('search_field')) {
        $field = $request->search_field;
        if ($field === 'nume') {
            // caută după numele tratamentului
            $query->where('nume', 'like', '%' . $request->search . '%');
        } elseif ($field === 'diagnostic') {
            // caută după numele diagnosticului (relație)
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


    #TRIMITERI
    $query = Trimitere::query()->where('id_pacient', $pacient->id);

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

    return view('pacienti.istoric', compact(
        'pacient', 'medici', 'programari', 'retete', 'diagnostice', 'tratamente', 'trimiteri'
    ));    
}

public function edit(string $id) { 
    $pacient = Pacient::where('id', $id)
        ->whereHas('medici', function ($q) {
            $q->where('id_medic', auth()->id());
        })
        ->firstOrFail();

    return view('pacienti.edit', compact('pacient')); }

    public function update(Request $request, string $id) {
        $request->validate([
            'nume'=>'required|string|max:100',
            'prenume'=>'required|string|max:100',
            'data_nasterii'=>'nullable|date',
            'cnp'=>'required|regex:/^[0-9]+$/|max:20',
            'telefon'=>'required|regex:/^[0-9]+$/|max:20',
            'adresa' => 'string|max:50',
            'asigurat/a'=>'boolean' ], [
            
            'nume.required' => 'Numele medicului este obligatoriu.',
            'nume.string' => 'Introduceti litere.',
            'nume.max' => 'Numele nu poate contine mai mult de 20 de caractere.',

            'prenume.required' => 'Prenumele medicului este obligatoriu.',
            'prenume.string' => 'Introduceti litere.',
            'prenume.max' => 'Prenumele nu poate contine mai mult de 20 de caractere.',

            'data_nasterii.required' => 'Introducerea adresei de e-mail este obligatorie.',

            'cnp.required' => 'Introducerea CNP-ului este obligatorie.',
            'cnp.regex' => 'Introduceti cifre.',
            'cnp.max' => 'Numele nu poate contine mai mult de 20 de caractere.',
            
            'adresa.required' => 'Numele medicului este obligatoriu.',
            'adresa.max' => 'Adresa nu poate contine mai mult de 50 de caractere.',

            'telefon.required' => 'Introducerea numarului de telefon este obligatorie.',
            'telefon.regex' => 'Introduceti cifre.',
            'telefon.max' => 'Numarul de telefon nu poate contine mai mult de 20 de caractere.',    

            'asigurat/a.required' => 'Selectarea asigurarii este obligatorie.'
        ]);
    $pacient = Pacient::where('id', $id)
        ->whereHas('medici', function ($q) {
            $q->where('id_medic', auth()->id());
        })
        ->firstOrFail();

    $pacient->update(
        $request->only([
            'nume','prenume','data_nasterii',
            'cnp','telefon','adresa','asigurat/a'
        ])
    );

    return redirect()->route('pacienti.index')
        ->with('success','Pacient modificat');
    }

    public function destroy(Pacient $pacienti) {
    // stergem doar legatura caci un pacient poate avea mai multi medici
    $pacienti->medici()->detach(auth()->id());

    return redirect()->route('pacienti.index')
        ->with('success','Pacient eliminat din lista ta');
    }
}
