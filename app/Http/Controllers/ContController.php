<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ContController extends Controller
{
    public function show()
    {
        $user = Auth::user();
        return view('cont', compact('user'));
    }

    public function edit()
    {
        $user = Auth::user();
        return view('cont-edit', compact('user'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'nume' => 'required|string|max:255',
            'prenume' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,'.$user->id,
            'telefon' => 'nullable|numeric',
            'adresa' => 'nullable|string|max:500',
        ]);

        $user->update($request->all());

        return redirect()->route('cont')->with('success', 'Datele au fost actualizate cu succes!');
    }

    public function destroy()
    {
        $user = Auth::user();
        $user->delete();
        Auth::logout();

        return redirect('/')->with('success', 'Contul a fost șters.');
    }
}