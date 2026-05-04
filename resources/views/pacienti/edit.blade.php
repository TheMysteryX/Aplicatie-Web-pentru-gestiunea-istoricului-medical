@extends('layouts.app')

@section('content')
<div class="top">
    <div class="titlu">
        <h1>Editează Pacient</h1>
    </div>
</div>

<div class="middle">
    <div class="card">
        <form method="POST" action="{{ route('pacienti.update', $pacient->id) }}" onsubmit="return validForm();">
            @csrf
            @method('PUT')

            <label for="nume">Nume:</label><p></p>
            <input type="text" name="nume" id="nume" class="form-input" value="{{ $pacient->nume }}"><p></p>
            <p></p>
            <label for="prenume">Prenume:</label><p></p>
            <input type="text" name="prenume" id="prenume" class="form-input" value="{{ $pacient->prenume }}"><p></p>
            <p></p>
            <label for="data_nasterii">Data nașterii:</label><p></p>
            <input type="date" name="data_nasterii" id="data_nasterii" class="form-input" value="{{ $pacient->data_nasterii }}"><p></p>
            <p></p>
            <label for="cnp">CNP:</label><p></p>
            <input type="text" name="cnp" id="cnp" class="form-input" value="{{ $pacient->cnp }}"><p></p>
            <p></p>
            <label for="telefon">Telefon:</label><p></p>
            <input type="text" name="telefon" id="telefon" class="form-input" value="{{ $pacient->telefon }}"><p></p>
            <p></p>
            <label for="adresa">Adresă:</label><p></p>
            <input type="text" name="adresa" id="adresa" class="form-input" value="{{ $pacient->adresa }}"><p></p>
            <p></p>
            <label for="asigurat/a">Asigurat/ă:</label><p></p>
            <select name="asigurat/a" id="asigurat/a" class="form-input">
                <option value="">Alege...</option> 
                <option value="1" {{ $pacient["asigurat/a"] ? 'selected' : '' }}>Da</option>
                <option value="0" {{ !$pacient["asigurat/a"] ? 'selected' : '' }}>Nu</option>
            </select><p></p>
            <p></p>
            <button type="submit">Salvează modificările</button>
        </form>
    </div>
</div>

<script>
function validForm() {
    const nume = document.getElementById('nume').value.trim();
    const prenume = document.getElementById('prenume').value.trim();
    const cnp = document.getElementById('cnp').value.trim();
    const telefon = document.getElementById('telefon').value.trim();
    const adresa = document.getElementById('adresa').value.trim();
    const asigurat = document.getElementById('asigurat/a').value.trim();

    if (!nume) { alert("Introdu numele pacientului!"); return false; }
    if (!prenume) { alert("Introdu prenumele pacientului!"); return false; }
    if (!cnp || !/^[0-9]+$/.test(cnp)) { alert("CNP invalid!"); return false; }
    if (!telefon || !/^[0-9]+$/.test(telefon)) { alert("Telefon invalid!"); return false; }
    if (!adresa) { alert("Introdu adresa pacientului!"); return false; }
    if (!asigurat) { alert("Introdu asigurarea pacientului!"); return false; }

    return true;
}
</script>
@endsection
