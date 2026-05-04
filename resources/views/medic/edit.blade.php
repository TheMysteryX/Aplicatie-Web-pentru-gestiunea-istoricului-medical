@extends('layouts.app')

@section('content')
<div class="top">
    <div class="titlu">
        <h1>Editează Medic</h1>
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
    <div class="card">
        <form method="POST" action="{{ route('medici.update', $medic->id) }}" onsubmit="return validForm();">
            @csrf
            @method('PUT')

            <label for="nume">Nume:</label><p></p>
            <input type="text" name="nume" id="nume" class="form-input" value="{{ $medic->nume }}"><p></p>
            <p></p>
            <label for="prenume">Prenume:</label><p></p>
            <input type="text" name="prenume" id="prenume" class="form-input" value="{{ $medic->prenume }}"><p></p>
            <p></p>
            <label for="email">Email:</label><p></p>
            <input type="email" name="email" id="email" class="form-input" value="{{ $medic->email }}"><p></p>
            <p></p>
            <label for="password">Parolă</label><p></p>
            <input type="password" name="password" id="password" class="form-input"><p></p>
            <p></p>
            <label for="spec_id">Specializare:</label><p></p>
            <select name="spec_id" id="spec_id" class="form-input">
                @foreach($specializari as $specializare)
                    <option value="{{ $specializare->id }}" {{ $medic->spec_id == $specializare->id ? 'selected' : '' }}>
                        {{ $specializare->nume }}
                    </option>
                @endforeach
            </select><p></p>
            <p></p>
            <label for="cnp">CNP:</label><p></p>
            <input type="text" name="cnp" id="cnp" class="form-input" value="{{ $medic->cnp }}"><p></p>
            <p></p>
            <label for="adresa">Adresă:</label><p></p>
            <input type="text" name="adresa" id="adresa" class="form-input" value="{{ $medic->adresa }}"><p></p>
            <p></p>
            <label for="telefon">Telefon:</label><p></p>
            <input type="text" name="telefon" id="telefon" class="form-input" value="{{ $medic->telefon }}"><p></p>
            <p></p>
            <button type="submit">Salvează modificările</button>
        </form>
    </div>
</div>

<script>
function validForm() {
    const nume = document.getElementById('nume').value.trim();
    const prenume = document.getElementById('prenume').value.trim();
    const email = document.getElementById('email').value.trim();
    const password = document.getElementById('password').value.trim();
    const cnp = document.getElementById('cnp').value.trim();
    const adresa = document.getElementById('adresa').value.trim();
    const telefon = document.getElementById('telefon').value.trim();

    if (!nume) { alert("Introdu numele medicului!"); return false; }
    if (!prenume) { alert("Introdu prenumele medicului!"); return false; }
    if (!email) { alert("Introdu emailul medicului!"); return false; }
    if (!password) {alert("Intodu parola!"); return false; }
    if (!cnp || !/^[0-9]+$/.test(cnp)) { alert("CNP invalid!"); return false; }
    if (!adresa) { alert("Introdu adresa medicului!"); return false; }
    if (!telefon || !/^[0-9]+$/.test(telefon)) { alert("Telefon invalid!"); return false; }

    return true;
}
</script>
@endsection
