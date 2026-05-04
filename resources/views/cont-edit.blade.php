@extends('layouts.app')

@section('content')
<div class="top">
    <div class="titlu">
        <h1>Contul meu</h1>
    </div>
</div>

<div class="middle">
    @if ($errors->any())
        <div class="card" style="color: red;">
            @foreach ($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif     
    @if (session('success'))
        <div class = "card" style="color:green">
            {{ session('success') }}
        </div>
    @endif  

        <div class="formular">
        <h2>Editare date personale...</h2>

        <form action="{{ route('cont.update') }}" method="POST" style="display:flex; flex-direction:column; gap:10px;" onsubmit="return validForm();">
            @csrf
            <p></p>
            <label>Nume:</label>
            <input type="text" name="nume" value="{{ old('nume', $user->nume) }}" required>
            <p></p>
            <label>Prenume:</label>
            <input type="text" name="prenume" value="{{ old('prenume', $user->prenume) }}" required>
            <p></p>
            <label>Email:</label>
            <input type="email" name="email" class="form-input" value="{{ old('email', $user->email) }}" required>
            <p></p>
            <label>Adresă:</label>
            <input type="text" name="adresa" value="{{ old('adresa', $user->adresa) }}">
            <p></p>
            <label>Telefon:</label>
            <input type="text" name="telefon" value="{{ old('telefon', $user->telefon) }}">
            <p></p>
            <label>Parolă nouă (opțional):</label>
            <input type="password" name="password" class="form-input">
            <p></p>
            <label>Confirmare parolă:</label>
            <input type="password" name="password_confirmation" class="form-input">
            <p></p>
            <button type="submit" class="btn">Salvează modificările</button>
        </form>
    </div>
</div>

<script>
    function validForm() {
        const nume = document.getElementsByName('nume')[0].value.trim();
        const prenume = document.getElementsByName('prenume')[0].value.trim();
        const email = document.getElementsByName('email')[0].value.trim();
        const telefon = document.getElementsByName('telefon')[0].value.trim();
        const password = document.getElementsByName('password')[0].value;
        const password_confirmation = document.getElementsByName('password_confirmation')[0].value;

        if (!nume) {
            alert('Numele este obligatoriu.');
            return false;
        }
        if (!prenume) {
            alert('Prenumele este obligatoriu.');
            return false;
        }
        if (!email) {
            alert('Emailul este obligatoriu.');
            return false;
        }
        const emailPattern = /^[^ ]+@[^ ]+\.[a-z]{2,3}$/;
        if (!email.match(emailPattern)) {
            alert('Adresa de email nu este validă.');
            return false;
        }
        if (telefon && !/^\d{10}$/.test(telefon)) {
            alert('Numărul de telefon trebuie să conțină exact 10 cifre.');
            return false;
        }
        if (password || password_confirmation) {
            if (password.length < 6) {
                alert('Parola trebuie să aibă cel puțin 6 caractere.');
                return false;
            }
            if (password !== password_confirmation) {
                alert('Parolele nu se potrivesc.');
                return false;
            }
        }
        return true;
    }
@endsection
