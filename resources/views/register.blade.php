@extends('layouts.app')
@section('content')
    <div class = top>
        <div class = "titlu">
            <h1>Inregistrare cont nou</h1>
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
        <div class = formular>
            @if (session('success'))
                <div style="color:green">
                    {{ session('success') }}
                </div>
            @endif               

            <form id="formular-login" action="{{ route('register') }}" method="POST">
                @csrf

                    <label for="nume">Nume:</label><p></p>
                    <input type="text" class="form-input" id="nume" name="nume"><p></p>
                    <p></p>
                    <label for="prenume">Prenume:</label><p></p>
                    <input type="text" class="form-input" id="prenume" name="prenume"><p></p>
                    <p></p>   
                     <label for="email">Email:</label><p></p>
                    <input type="email" class="form-input" id="email" name="email"><p></p>
                    <p></p>
                    <label for="password">Parola:</label><p></p>
                    <input type="password" class="form-input" id="password" name="password"><p></p>
                    <p></p>
                    <label for="rol">Rol:</label><p></p>
                    <input type="text" class="form-input" id="rol" name="rol"><p></p>
                    <p></p>
                    <label>Specializarea (daca este cazul):</label><p></p>
                    <select name="spec_id" id="spec-med" class="form-input"><p></p>
                        <option value="">Alege...</option>
                        @foreach($specializari as $spec)
                            <option value="{{ $spec->id }}">
                                {{ $spec->nume }}
                            </option>
                        @endforeach
                    </select><p></p>
                    <p></p>
                    <label for="cnp">CNP:</label><p></p>
                    <input type="text" class="form-input" id="cnp" name="cnp"><p></p>
                    <p></p>
                    <label for="data_nasterii">Data nasterii:</label><p></p>
                    <input type="date" class="form-input" id="data_nasterii" name="data_nasterii"><p></p>
                    <p></p>
                    <label for="adresa">Adresa:</label><p></p>
                    <textarea name = "adresa" class="form-input" id="adresa"></textarea><p></p>
                    <p></p>
                    <label for="telefon">Telefon:</label><p></p>
                    <input type="text" class="form-input" id="telefon" name="telefon"><p></p>
                    <p></p>
                    <label>Asigurat (daca este cazul):</label><p></p>
                    <select name="asigurat/a" id="asigurat" class="form-input"><p></p>
                        <option value="">Alege...</option>
                        <option value="1">Da</option>
                        <option value="0">Nu</option>
                    </select><p></p>
                    <input type="submit" class="form-button" value="Creeaza cont">
            </form>
        </div>
    </div>

        <script>
        document.getElementById('formular-login').addEventListener('submit', function(e) {
            let nume = document.getElementById('nume').value.trim();
            let prenume = document.getElementById('prenume').value.trim();
            let email = document.getElementById('email').value.trim();
            let parola = document.getElementById('parola').value.trim();
            let rol = document.getElementById('rol').value.trim();
            let spec = document.getElementById('spec-med').value.trim();
            let cnp = document.getElementById('cnp').value.trim();
            let adresa = document.getElementById('adresa').value.trim();
            let telefon = document.getElementById('telefon').value.trim();

            if (!nume) {
                alert('Numele este obligatoriu!');
                e.preventDefault();
                return false;
            }
            if (!prenume) {
                alert('Prenumele este obligatoriu!');
                e.preventDefault();
                return false;
            }
            if (!email) {
                alert('Email-ul este obligatoriu!');
                e.preventDefault();
                return false;
            }
            if (!parola) {
                alert('Parola este obligatorie!');
                e.preventDefault();
                return false;
            }
            if (!rol) {
                alert('Rolul este obligatoriu!');
                e.preventDefault();
                return false;
            }
            if (rol === 'medic' && !spec) {
                alert('Specializarea este obligatorie pentru medici!');
                e.preventDefault();
                return false;
            }
            if (!cnp) {
                alert('CNP-ul este obligatoriu!');
                e.preventDefault();
                return false;
            }
            if (!adresa) {
                alert('Adresa este obligatorie!');
                e.preventDefault();
                return false;
            }
            if (!telefon) {
                alert('Telefonul este obligatoriu!');
                e.preventDefault();
                return false;
            }
            return true;
        });
    </script>
@endsection