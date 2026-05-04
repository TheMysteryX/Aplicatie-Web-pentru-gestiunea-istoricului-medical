<?php

namespace App\Http\Controllers;

use App\Models\SolicitareProgramare;
use App\Models\User;
use Illuminate\Http\Request;

class PacientSolicitareController extends Controller
{
    public function create()
    {
        $medici = User::where('rol', 'medic')->get();

        return view('pacienti.solicitare.create', compact('medici'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'medic_id' => 'required|exists:users,id',
            'data_start' => 'required|date|after:today',
            'data_end' => 'required|date|after:data_start',
            'mesaj' => 'nullable|string'
        ]);

        SolicitareProgramare::create([
            'pacient_id' => auth()->user()->pacient->id,
            'medic_id' => $request->medic_id,
            'data_start' => $request->data_start,
            'data_end' => $request->data_end,
            'mesaj' => $request->mesaj,
        ]);

        return redirect()->route('pacient.dashboard')
                         ->with('success', 'Solicitare trimisă!');
    }
}