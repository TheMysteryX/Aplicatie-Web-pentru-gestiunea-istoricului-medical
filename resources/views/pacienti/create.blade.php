@extends('layouts.app')

@section('content')
<div class="top">
    <div class="titlu">
        <h1>Administrare</h1>
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
        <div class="card" style="color: green;">
            {{ session('success') }}
        </div>
    @endif  

    <div class="card">
        <h2>Pacienți</h2>
        <form method="POST" action="{{ route('pacienti.store') }}" onsubmit="return validForm();">
            @csrf
            <label>Nume:</label><p></p>
            <input type="text" name="nume" id="nume" class="form-input"><p></p>
            <p></p>
            <label>Prenume:</label><p></p>
            <input type="text" name="prenume" id="prenume" class="form-input"><p></p>
            <p></p>
            <label>Data Nasterii:</label><p></p>
            <input type="date" name="data_nasterii" id="data_nasterii" class="form-input"><p></p>
            <p></p>
            <label>CNP:</label><p></p>
            <input type="text" name="cnp" id="cnp" class="form-input"><p></p>  
            <p></p>
            <label>Adresa:</label><p></p>
            <input type="text" name="adresa" id="adresa" class="form-input"><p></p>
            <p></p>
            <label>Telefon:</label><p></p>
            <input type="text" name="telefon" id="telefon" class="form-input"><p></p>
            <p></p>
            <label>Asigurat/ă:</label><p></p>
            <select name="asigurat/a" id="asigurat/a" class="form-input">
                <option value="">Alege...</option>
                <option value="1">Da</option>
                <option value="0">Nu</option>
            </select><p></p>            
            <p></p>
            <button type="submit">Salvează</button>
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
