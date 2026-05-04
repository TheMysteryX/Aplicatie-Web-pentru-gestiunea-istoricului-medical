<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Specializare;
use App\Models\Pacient;
use App\Models\Programare;
use App\Models\Reteta;
use App\Models\Diagnostic;
use App\Models\Tratament;
use App\Models\Trimitere;
use App\Models\SolicitareProgramare;
use Carbon\Carbon;
class MedicController extends Controller
{
    public function index(Request $request) {
        $medici = User::where('rol','medic')->with('specializare')->get();
        return view('medic.dashboard', compact('medici'));   
    }


public function dashboard()
{
    $medicId = auth()->id();

    $solicitari = SolicitareProgramare::where('medic_id', $medicId)
        ->where('status', 'trimisa')
        ->with('pacient')
        ->get();


    //programari urmatoare, ultimele, intarziate
    $nextProgram = Programare::where('id_medic', $medicId)
        ->where('data', '>=', now())
        ->orderBy('data', 'asc')
        ->first();

    $lastProgram = Programare::where('id_medic', $medicId)
        ->where('data', '<', now())
        ->orderBy('data', 'desc')
        ->take(5)
        ->get();

    $delayedPrograms = Programare::where('id_medic', $medicId)
        ->where('status', 'viitoare')
        ->whereDate('data', '<', Carbon::now())
        ->get();


    // statistici pentru saptamana curenta
    $weekStart = Carbon::now()->startOfWeek();
    $weekEnd = Carbon::now()->endOfWeek();
    $weekPrograms = Programare::where('id_medic', $medicId)
        ->whereBetween('data', [$weekStart, $weekEnd])
        ->get();

    $stats = [
        'total'     => $weekPrograms->count(),
        'finalizate'=> $weekPrograms->where('status', 'finalizata')->count(),
        'amanate'   => $weekPrograms->where('status', 'amanata')->count(),
    ];

    // retete si tratamente care expira în 7 zile
    $expiringPrescriptions = Reteta::with('pacient', 'diagnostic')->where('id_medic', $medicId)
        ->whereBetween('data_expirare', [now(), now()->addDays(7)])
        ->get();

    $expiringTratamente = Tratament::with('pacient', 'diagnostic')->where('id_medic', $medicId)
        ->whereBetween('data_sfarsit', [now(), now()->addDays(7)])
        ->get();

    return view('medic.dashboard', compact('solicitari',
        'nextProgram', 'lastProgram',
        'stats', 'delayedPrograms', 'expiringPrescriptions', 'expiringTratamente'
    ));
}

    public function create() {
        $specializari = Specializare::all();
        return view('medic.create', compact('specializari'));
    }

    public function store(Request $date) {
        $date->validate([
            'nume'=>'required|string|max:20',
            'prenume'=>'required|string|max:20',
            'email'=>'required|email|unique:users|max:20',
            'password'=>'required|min:6',
            'spec_id'=>'required|exists:specializari,id',
            'cnp'=>'required|regex:/^[0-9]+$/|max:20',
            'adresa'=>'required|max:50',
            'telefon'=>'required|regex:/^[0-9]+$/|max:20'
        ], [
            'nume.required' => 'Numele medicului este obligatoriu.',
            'nume.string' => 'Introduceti litere.',
            'nume.max' => 'Numele nu poate contine mai mult de 20 de caractere.',

            'prenume.required' => 'Prenumele medicului este obligatoriu.',
            'prenume.string' => 'Introduceti litere.',
            'prenume.max' => 'Prenumele nu poate contine mai mult de 20 de caractere.',

            'email.required' => 'Introducerea adresei de e-mail este obligatorie.',
            'email.unique' => 'Aceasta adresa de e-mail exista deja.',
            'email.max' => 'Adresa de e-mail nu poate contine mai mult de 20 de caractere.',

            'spec_id.required' => 'Selectarea specializarii este obligatorie.',


            'password.required' => 'Introducerea parolei este obligatorie',
            'password.min'=>'Parola trebuie sa contina cel putin 6 caractere.',

            'cnp.required' => 'Introducerea CNP-ului este obligatorie.',
            'cnp.regex' => 'Introduceti cifre.',
            'cnp.max' => 'Numele nu poate contine mai mult de 20 de caractere.',
            
            'adresa.required' => 'Numele medicului este obligatoriu.',
            'adresa.max' => 'Adresa nu poate contine mai mult de 50 de caractere.',

            'telefon.required' => 'Introducerea numarului de telefon este obligatorie.',
            'telefon.regex' => 'Introduceti cifre.',
            'telefon.max' => 'Numarul de telefon nu poate contine mai mult de 20 de caractere.',            

        ]);
        User::create([
            'nume'=>$date->nume,
            'prenume'=>$date->prenume,
            'email'=>$date->email,
            'password'=>bcrypt($date->password),
            'rol'=>'medic',
            'spec_id'=>$date->spec_id,
            'cnp'=>$date->cnp,
            'adresa'=>$date->adresa,
            'telefon'=>$date->telefon

        ]);
        return redirect()->route('admin.dashboard')->with('success','Medic adaugat');
    }

    public function show(Request $request, $id) {
        $medic = User::where('rol', 'medic')->findOrFail($id);

        
        $query = Programare::with('pacient')
            ->where('id_medic', $id);

        if ($request->filled('data')) {
            $query->whereDate('data', $request->data);
        }

        if ($request->filled('sort_by') && in_array($request->sort_by, ['data'])) {
                $query->orderBy('data', $request->direction ?? 'asc');
            }
        $limit = $request->get('limit', 10);
        $programari = $query->paginate($limit);



        $query = Reteta::with(['pacient', 'medic','diagnostic'])
            ->where('id_medic', $id);

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



        $query = Diagnostic::with(['pacient', 'medic','programare'])
            ->where('id_medic', $id);


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


        
        $query = Tratament::with(['pacient', 'medic','diagnostic'])
            ->where('id_medic', $id);

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


     
        $query = Trimitere::query()->where('id_medic', $id);


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

        return view('medic.show', compact(
            'medic', 'programari', 'retete', 'diagnostice', 'tratamente', 'trimiteri'
        ));
}
    
    public function edit(string $id) {
        $medic = User::findOrFail($id);
        $specializari = Specializare::all();
        return view('medic.edit', compact('medic','specializari'));
    }

    public function update(Request $date, string $id) {
        $medic = User::findOrFail($id);
        
        $date->validate([
            'nume'=>'required|string|max:20',
            'prenume'=>'required|string|max:20',
            'email'=>'required|email|unique:users,email,'.$medic->id,
            'spec_id'=>'required|exists:specializari,id',
            'password'=>'required|min:6',
            'cnp'=>'required|regex:/^[0-9]+$/|max:20',
            'adresa'=>'required|max:100',
            'telefon'=>'required|regex:/^[0-9]+$/|max:20'
        ], [
            'nume.required' => 'Numele medicului este obligatoriu.',
            'nume.string' => 'Introduceti litere.',
            'nume.max' => 'Numele nu poate contine mai mult de 20 de caractere.',

            'prenume.required' => 'Prenumele medicului este obligatoriu.',
            'prenume.string' => 'Introduceti litere.',
            'prenume.max' => 'Prenumele nu poate contine mai mult de 20 de caractere.',

            'email.required' => 'Introducerea adresei de e-mail este obligatorie.',
            'email.unique' => 'Aceasta adresa de e-mail exista deja.',
            'email.max' => 'Adresa de e-mail nu poate contine mai mult de 20 de caractere.',

            'spec_id.required' => 'Selectarea specializarii este obligatorie.',

            'password.required' => 'Introducerea parolei este obligatorie',
            'password.min'=>'Parola trebuie sa contina cel putin 6 caractere.',

            'cnp.required' => 'Introducerea CNP-ului este obligatorie.',
            'cnp.regex' => 'Introduceti cifre.',
            'cnp.max' => 'Numele nu poate contine mai mult de 20 de caractere.',
            
            'adresa.required' => 'Numele medicului este obligatoriu.',
            'adresa.max' => 'Adresa nu poate contine mai mult de 50 de caractere.',

            'telefon.required' => 'Introducerea numarului de telefon este obligatorie.',
            'telefon.regex' => 'Introduceti cifre.',
            'telefon.max' => 'Numarul de telefon nu poate contine mai mult de 20 de caractere.',
        ]);
        $data = $date->only('nume','prenume','email','spec_id','cnp','adresa','telefon');
        if ($date->filled('password')) {
            $data['password'] = bcrypt($date->password);
        }
        
        $medic->update($data);
        return redirect()->route('admin.dashboard')->with('success','Medic modificat');
    }

    public function destroy($id) {
        $medic = User::where('rol', 'medic')->findOrFail($id);
        $medic->delete();
        return redirect()->route('admin.dashboard')->with('success','Medic sters');
    }
}
