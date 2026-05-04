<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Specializare;
use App\Models\Pacient;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller {
    public function showLogin() {
        return view('login');
    }

    public function login(Request $request) {
        $date = $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        if (Auth::attempt($date)) {
            $request->session()->regenerate();

        if (Auth::user()->isAdmin()) {
            return redirect()->route('admin.dashboard');
        }

        if (Auth::user()->isMedic()) {
            return redirect()->route('medic.dashboard');
        }

        if (Auth::user()->isPacient()) {
            return redirect()->route('pacient.dashboard');
        }
        }

        return back()->withErrors(['email' => 'Email sau parolă incorectă.']);
    }

    public function showRegister() {
        $specializari = Specializare::all();
        return view('register', compact('specializari'));
    }

    public function register(Request $request) {
        $rules = [
            'nume' => 'required|string|max:20',
            'prenume'  => 'required|string|max:100',
            'email'      => 'required|email|unique:users,email|max:50',
            'password'   => 'required|min:6',
            'rol'       => 'required|in:admin,medic,pacient',
            'cnp'=>'required|regex:/^[0-9]+$/|max:20',
            'data_nasterii' => 'required|date',
            'adresa'    => 'required|max:50',
            'telefon'      => 'required|regex:/^[0-9]+$/|max:20',
        ];

        if ($request->rol === 'medic') {
            $rules['spec_id'] = 'required|exists:specializari,id';
        } else {
            $rules['spec_id'] = 'nullable';
        }

        if ($request->rol === 'pacient') {
            $rules['asigurat/a'] = 'required|boolean';
        } else {
            $rules['asigurat/a'] = 'nullable';
        }

        $data = $request->validate($rules, [
            'nume.required' => 'Numele este obligatoriu.',
            'nume.string' => 'Introduceti litere.',
            'nume.max' => 'Numele nu poate contine mai mult de 20 de caractere.',

            'prenume.required' => 'Prenumele este obligatoriu.',
            'prenume.string' => 'Introduceti litere.',
            'prenume.max' => 'Prenumele nu poate contine mai mult de 20 de caractere.',

            'email.required' => 'Introducerea adresei de e-mail este obligatorie.',
            'email.unique' => 'Aceasta adresa de e-mail exista deja.',
            'email.max' => 'Adresa de e-mail nu poate contine mai mult de 20 de caractere.',

            'spec_id.required' => 'Selectarea specializarii este obligatorie.',

            'password.required' => 'Introducerea parolei este obligatorie',
            'password.min'=>'Parola trebuie sa contina cel putin 6 caractere.',

            'rol.required' => 'Introducerea rolului este obligatorie',
            'rol.in'=>'Rolul introdus nu exista (admin, medic, pacient)',

            'cnp.required' => 'Introducerea CNP-ului este obligatorie.',
            'cnp.regex' => 'Introduceti cifre.',
            'cnp.max' => 'Numele nu poate contine mai mult de 20 de caractere.',
            
            'data_nasterii.required' => 'Introducerea datei de nastere este obligatorie.',
            'data_nasterii.date' => 'Introduceti o data valida.',

            'adresa.required' => 'Numele medicului este obligatoriu.',
            'adresa.max' => 'Adresa nu poate contine mai mult de 50 de caractere.',

            'telefon.required' => 'Introducerea numarului de telefon este obligatorie.',
            'telefon.regex' => 'Introduceti cifre.',
            'telefon.max' => 'Numarul de telefon nu poate contine mai mult de 20 de caractere.',

            'asigurat/a.required' => 'Introducerea statutului de asigurat este obligatorie.',
            'asigurat/a.boolean' => 'Statusul de asigurat trebuie sa fie 1 (asigurat) sau 0 (neasigurat).',
        ]);

        $data['password'] = Hash::make($data['password']);

        $user = User::create($data);

        if ($user->rol === 'pacient') {
            Pacient::create([
            'nume' => $user->nume,
            'prenume' => $user->prenume,
            'cnp' => $user->cnp,
            'telefon' => $user->telefon,
            'adresa' => $user->adresa,
            'user_id' => $user->id,
            'data_nasterii' => $user->data_nasterii,
            'asigurat/a' => $request->input('asigurat/a'),
            ]);
        }

        Auth::login($user);

        if (Auth::user()->isAdmin()) {
            return redirect()->route('admin.dashboard');
        }

        if (Auth::user()->isMedic()) {
            return redirect()->route('medic.dashboard');
        }

        if (Auth::user()->isPacient()) {
            return redirect()->route('pacient.dashboard');
        }
    }

    public function logout(Request $request) {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }
}
