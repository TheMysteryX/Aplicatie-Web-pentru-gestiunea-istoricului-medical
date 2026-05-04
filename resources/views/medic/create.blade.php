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
        <div class = "card" style="color:green">
            {{ session('success') }}
        </div>
    @endif  
    <div class="card">
        <h2>Medici</h2>
        <form method="POST" action="{{ route('medici.store') }}" onsubmit="return validForm();">
            @csrf
            <label>Nume:</label><p></p>
            <input type="text" name="nume" id="nume" class="form-input"><p></p>
            <p></p>
            <label>Prenume:</label><p></p>
            <input type="text" name="prenume" id="prenume" class="form-input"><br><p></p>
            <p></p>
            <label>Specializare:</label><p></p>
            <select name="spec_id"  id="spec-med" class="form-input">
                <option value="">Alege...</option>
                @foreach($specializari as $spec)
                    <option value="{{ $spec->id }}">{{ $spec->nume }}</option>
                @endforeach
            </select><p></p>
            <p></p>
        <label>Email</label><p></p>
        <input type="text" name="email" id="" class="form-input"><p></p>
            <p></p>
        <label>Parola</label><p></p>
        <input type="password" name="password" id="password" class="form-input"><p></p>
        <p></p>
        <label>CNP</label><p></p>
        <input type="text" name="cnp" id="cnp" class="form-input"><p></p>  
        <p></p>
        <label>Adresa</label><p></p>
        <input type="text" name="adresa" id="adresa" class="form-input"><p></p>
        <p></p>
        <label>Telefon</label><p></p>
        <input type="text" name="telefon" id="telefon" class="form-input"><p></p>
        <p></p>
        <button type="submit">Salveaza</button>
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