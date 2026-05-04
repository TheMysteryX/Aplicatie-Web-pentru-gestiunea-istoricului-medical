<?php

namespace App\Http\Controllers;

use App\Models\SolicitareProgramare;

class MedicSolicitareController extends Controller
{
    public function index()
    {
        // $solicitari = SolicitareProgramare::where('medic_id', auth()->id())
        //             ->where('status', 'trimisa')
        //             ->get();

        // return view('medic.solicitari.index', compact('solicitari'));
    }

    public function respinge(SolicitareProgramare $solicitare)
    {
        $solicitare->update(['status' => 'respinsa']);

        return back()->with('success', 'Solicitare respinsă.');
    }
}