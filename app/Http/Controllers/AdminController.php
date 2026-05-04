<?php

namespace App\Http\Controllers;

use App\Models\Specializare;
use App\Models\User;
use App\Models\Pacient;
use App\Models\Programare;
use App\Models\Reteta;
use App\Models\Diagnostic;
use App\Models\Tratament;
use App\Models\Trimitere;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AdminController extends Controller
{
    public function index(Request $request)
    {
        //statistici generale   
        $today = Carbon::today();
        $weekStart = Carbon::now()->startOfWeek();
        $monthStart = Carbon::now()->startOfMonth();
        $yearStart = Carbon::now()->startOfYear();

        $statsFor = function ($model) use ($today, $weekStart, $monthStart, $yearStart) {
            return [
                'today' => $model::whereDate('created_at', $today)->count(),
                'week'  => $model::whereBetween('created_at', [$weekStart, Carbon::now()])->count(),
                'month' => $model::whereBetween('created_at', [$monthStart, Carbon::now()])->count(),
                'year'  => $model::whereBetween('created_at', [$yearStart, Carbon::now()])->count(),
                'total' => $model::count(),
            ];
        };

        $stats = [
            'pacienti'     => $statsFor(Pacient::class),
            'programari'   => $statsFor(Programare::class),
            'retete'       => $statsFor(Reteta::class),
            'diagnostice'  => $statsFor(Diagnostic::class),
            'tratamente'   => $statsFor(Tratament::class),
            'trimiteri'    => $statsFor(Trimitere::class),
        ];

        
        //FILTRARE SPECIALIZARI
        $specQuery = Specializare::query();
        if ($request->filled('nume')) {
            $specQuery->where('nume', 'like', '%' . $request->nume . '%');
        }
        if ($request->filled('litera')) {
            $specQuery->where('nume', 'like', $request->litera . '%');
        }
        if ($request->filled('direction')) {
            $specQuery->orderBy('nume', $request->direction);
        }
        $specLimit = $request->get('limit', 10);
        $specializari = $specQuery->paginate($specLimit)->withQueryString();


        //FILTRARE MEDICI
        $mediciQuery = User::where('rol', 'medic')->with('specializare');
        if ($request->filled('search') && $request->filled('search_field')) {
            $field = $request->search_field;
            if (in_array($field, ['nume','prenume','cnp'])) {
                $mediciQuery->where($field, 'like', '%' . $request->search . '%');
            }
            if ($field == 'spec') {
                $mediciQuery->whereHas('specializare', function($q) use ($request) {
                    $q->where('nume', 'like', '%' . $request->search . '%');
                });
            }
        }
        if ($request->filled('litera')) {
            $mediciQuery->where('nume', 'like', $request->litera.'%');
        }
        if ($request->filled('sort_by')) {
            $direction = $request->get('direction', 'asc');

            if (in_array($request->sort_by, ['nume','prenume', 'cnp'])) {
                $mediciQuery->orderBy($request->sort_by, $direction);

            } elseif ($request->sort_by === 'spec') {
                $mediciQuery->orderBy(Specializare::select('nume')->whereColumn('specializari.id', 'users.spec_id'),$direction);
            }
        }
        $mediciLimit = $request->get('limit', 10);
        $medici = $mediciQuery->paginate($mediciLimit)->withQueryString();


        //FILTRARE PACIENTI
        $pacQuery = Pacient::query();

        if (auth()->user()->isMedic()) {
            $pacQuery->whereHas('medici', function ($q) {
                $q->where('id_medic', auth()->id());
            });
        }

        if ($request->filled('search') && $request->filled('search_field')) {  // cautare generala dupa camp selectat
            $field = $request->search_field;
            if (in_array($field, ['nume','prenume','cnp'])) {
                $pacQuery->where($field, 'like', '%' . $request->search . '%');
            }
        }

        if ($request->filled('litera')) {
            $litera = $request->litera;
            $pacQuery->where('nume', 'like', $litera.'%');
        }

        if ($request->filled('sort_by') && in_array($request->sort_by, ['nume','prenume','data_nasterii'])) {
            $pacQuery->orderBy($request->sort_by, $request->direction ?? 'asc');
        }

        $pacLimit = $request->get('limit', 10);
        $pacienti = $pacQuery->paginate($pacLimit);


        //FILTRARE PROGRAMARI
        $progQuery = Programare::with('pacient');

        if ($request->filled('nume')) {
            $progQuery->whereHas('pacient', function ($q) use ($request) {
                $q->where('nume', 'like', '%'.$request->nume.'%')
                ->orWhere('prenume', 'like', '%'.$request->nume.'%');
            });
        }

        if ($request->filled('medic')) {
            $progQuery->whereHas('medic', function ($q) use ($request) {
                $q->where('nume', 'like', '%'.$request->medic.'%')
                ->orWhere('prenume', 'like', '%'.$request->medic.'%');
            });
        }  

        if ($request->filled('data')) {
            $progQuery->whereDate('data', $request->data);
        }

        if ($request->filled('status')) {
            $progQuery->where('status', $request->status);
        }      

        if ($request->filled('sort_by') && in_array($request->sort_by, ['nume','data'])) {
            if ($request->sort_by === 'nume') {
                $progQuery->join('pacienti', 'pacienti.id', '=', 'programari.id_pacient')
                        ->orderBy('pacienti.nume', $request->direction ?? 'asc')
                        ->select('programari.*');
            } elseif ($request->sort_by === 'medic') {
                $progQuery->join('users as medici', 'medici.id', '=', 'programari.id_medic')
                        ->orderBy('medici.nume', $request->direction ?? 'asc')
                        ->select('programari.*');
            } else {
                $progQuery->orderBy('data', $request->direction ?? 'asc');
            }
        }
        $today = now();
        $progLimit = $request->get('limit', 10);
        $programari = $progQuery->paginate($progLimit)->withQueryString();


        //FILTRARE RETETE
        $retQuery = Reteta::with(['pacient', 'medic','diagnostic']);

        if ($request->filled('diagnostic')) {
            $retQuery->whereHas('diagnostic', function($q) use ($request) {
                $q->where('nume', 'like', '%' . $request->diagnostic . '%');
            });
        }

        if ($request->filled('nume')) {
            $retQuery->whereHas('pacient', function ($q) use ($request) {
                $q->where('nume', 'like', '%'.$request->nume.'%')
                ->orWhere('prenume', 'like', '%'.$request->nume.'%');
            });
        }

        if ($request->filled('medic')) {
            $retQuery->whereHas('medic', function ($q) use ($request) {
                $q->where('nume', 'like', '%'.$request->medic.'%')
                ->orWhere('prenume', 'like', '%'.$request->medic.'%');
            });
        }  

        if ($request->filled('data_emitere')) {
            $retQuery->whereDate('data_emitere', $request->data_emitere);
        }
        if ($request->filled('data_expirare')) {
            $retQuery->whereDate('data_expirare', $request->data_expirare);
        }

        if ($request->filled('litera')) {
            $litera = strtoupper($request->litera);
            $retQuery->whereHas('diagnostic', function($q) use ($litera) {
                $q->where('nume', 'like', $litera.'%');
            });
        }

        if ($request->filled('sort_by')) {
            $direction = $request->get('direction', 'asc');
            if (in_array($request->sort_by, ['data_emitere','data_expirare'])) {
                $retQuery->orderBy($request->sort_by, $direction);
            } elseif ($request->sort_by === 'diagnostic') {
                $retQuery->join('diagnostice','retete.id_diagnostic','=','diagnostice.id')
                    ->orderBy('diagnostice.nume', $direction)
                    ->select('retete.*');
            }
            elseif ($request->sort_by === 'nume') {
                $retQuery->join('pacienti', 'pacienti.id', '=', 'retete.id_pacient')
                        ->orderBy('pacienti.nume', $request->direction ?? 'asc')
                        ->select('retete.*');
            } elseif ($request->sort_by === 'medic') {
                $retQuery->join('users as medici', 'medici.id', '=', 'retete.id_medic')
                        ->orderBy('medici.nume', $request->direction ?? 'asc')
                        ->select('retete.*');
            }        
        }
        $retLimit = $request->get('limit', 10);
        $retete = $retQuery->paginate($retLimit)->withQueryString();    


        //FILTRARE DIAGNOSTICE
        $diagQuery = Diagnostic::with(['pacient', 'medic','programare']);

    if ($request->filled('nume')) {
            $diagQuery->where('nume', $request->nume);
        }
        if ($request->filled('pacient')) {
            $diagQuery->whereHas('pacient', function ($q) use ($request) {
                $q->where('nume', 'like', '%'.$request->pacient.'%')
                ->orWhere('prenume', 'like', '%'.$request->pacient.'%');
            });
        }
        if ($request->filled('medic')) {
            $diagQuery->whereHas('medic', function ($q) use ($request) {
                $q->where('nume', 'like', '%'.$request->medic.'%')
                ->orWhere('prenume', 'like', '%'.$request->medic.'%');
            });
        }

        if ($request->filled('data')) {
            $diagQuery->whereDate('data', $request->data);
        }

        if ($request->filled('litera')) {
            $litera = $request->litera;
            $diagQuery->where('nume', 'like', $litera.'%');
        }

        if (in_array($request->sort_by, ['nume','data'])) {
            $diagQuery->orderBy($request->sort_by, $direction);
        } elseif ($request->sort_by === 'pacient') {
                $diagQuery->join('pacienti', 'pacienti.id', '=', 'diagnostice.id_pacient')
                        ->orderBy('pacienti.nume', $request->direction ?? 'asc')
                        ->select('diagnostice.*');
            } elseif ($request->sort_by === 'medic') {
                $diagQuery->join('users as medici', 'medici.id', '=', 'diagnostice.id_medic')
                        ->orderBy('medici.nume', $request->direction ?? 'asc')
                        ->select('diagnostice.*');
            }

        $diagLimit = $request->get('limit', 10);
        $diagnostice = $diagQuery->paginate($diagLimit)->withQueryString();


        //FILRTARE TRATAMENTE
        $tratQuery = Tratament::with(['pacient', 'medic','diagnostic']);

        if ($request->filled('search') && $request->filled('search_field')) {
            $field = $request->search_field;
            if ($field === 'nume') {
                $tratQuery->where('nume', 'like', '%' . $request->search . '%');
            } elseif ($field === 'diagnostic') {
                $tratQuery->whereHas('diagnostic', function($q) use ($request) {
                    $q->where('nume', 'like', '%' . $request->search . '%');
                });
            } elseif ($field === 'pacient') {
                $tratQuery->whereHas('pacient', function($q) use ($request) {
                    $q->where('nume', 'like', '%' . $request->search . '%')
                    ->orWhere('prenume', 'like', '%' . $request->search . '%');
                });
            } elseif ($field === 'medic') {
                $tratQuery->whereHas('medic', function($q) use ($request) {
                    $q->where('nume', 'like', '%' . $request->search . '%')
                    ->orWhere('prenume', 'like', '%' . $request->search . '%');
                });
            }
        }
        
        if ($request->filled('data_inceput')) {
            $tratQuery->whereDate('data_inceput', $request->data_inceput);
        }
        if ($request->filled('data_sfarsit')) {
            $tratQuery->whereDate('data_sfarsit', $request->data_sfarsit);
        }

        if ($request->filled('litera')) {
            $litera = $request->litera;
            $tratQuery->where('nume', 'like', $litera.'%');
        }

        if ($request->filled('sort_by')) {
            $direction = $request->get('direction', 'asc');

            if (in_array($request->sort_by, ['nume','data_inceput','data_sfarsit'])) {
                $tratQuery->orderBy($request->sort_by, $direction);

            } elseif ($request->sort_by === 'diagnostic') {
                $tratQuery->whereHas('diagnostic') ->orderBy(Diagnostic::select('nume')
                    ->whereColumn('diagnostice.id', 'tratamente.id_diagnostic'),$direction);
            }
            elseif ($request->sort_by === 'pacient') {
                $tratQuery->join('pacienti', 'pacienti.id', '=', 'tratamente.id_pacient')
                        ->orderBy('pacienti.nume', $request->direction ?? 'asc')
                        ->select('tratamente.*');
            } elseif ($request->sort_by === 'medic') {
                $tratQuery->join('users as medici', 'medici.id', '=', 'tratamente.id_medic')
                        ->orderBy('medici.nume', $request->direction ?? 'asc')
                        ->select('tratamente.*');
            }
        }

        $tratLimit = $request->get('limit', 10);
        $tratamente = $tratQuery->paginate($tratLimit)->withQueryString();


        //FILTRARE TRIMITERI
        $trimQuery = Trimitere::query();

        if ($request->filled('search') && $request->filled('search_field')) {
            $field = $request->search_field;
            if (in_array($field, ['titlu','locatie'])) {
                $trimQuery->where($field, 'like', '%' . $request->search . '%');
            }
            elseif ($field === 'pacient') {
                $trimQuery->whereHas('pacient', function ($q) use ($request) {
                    $q->where('nume', 'like', '%'.$request->search.'%')
                    ->orWhere('prenume', 'like', '%'.$request->search.'%');
                });
            }
            elseif ($field === 'medic') {
                $trimQuery->whereHas('medic', function ($q) use ($request) {
                    $q->where('nume', 'like', '%'.$request->search.'%')
                    ->orWhere('prenume', 'like', '%'.$request->search.'%');
                });
            }
        }

        if ($request->filled('data_emitere')) {
            $trimQuery->whereDate('data_emitere', $request->data_emitere);
        }
        if ($request->filled('data_expirare')) {
            $trimQuery->whereDate('data_expirare', $request->data_expirare);
        }

        if ($request->filled('litera')) {
            $litera = $request->litera;
            $trimQuery->where('titlu', 'like', $litera.'%');
        }

        if ($request->filled('sort_by') && in_array($request->sort_by, ['titlu','locatie','data_emitere','data_expirare'])) {
            $trimQuery->orderBy($request->sort_by, $request->direction ?? 'asc');
            } elseif ($request->sort_by === 'pacient') {
                    $trimQuery->join('pacienti', 'pacienti.id', '=', 'trimiteri.id_pacient')
                            ->orderBy('pacienti.nume', $request->direction ?? 'asc')
                            ->select('trimiteri.*');
                } elseif ($request->sort_by === 'medic') {
                    $trimQuery->join('users as medici', 'medici.id', '=', 'trimiteri.id_medic')
                            ->orderBy('medici.nume', $request->direction ?? 'asc')
                            ->select('trimiteri.*');
        }

        $trimLimit = $request->get('limit', 10);
        $trimiteri = $trimQuery->paginate($trimLimit)->withQueryString();


        return view('admin.dashboard', compact(
            'medici', 'specializari', 'stats',
            'pacienti', 'programari', 'retete', 'diagnostice', 'tratamente', 'trimiteri'
        ));
    }
}
