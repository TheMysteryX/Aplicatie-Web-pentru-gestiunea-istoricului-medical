@extends('layouts.app')

@section('content')
<div class="top">
    <h1>Adaugă Tratament</h1>
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
    <form method="POST" action="{{ route('tratamente.store') }}" onsubmit="return validForm();">
        @csrf
            <label>Numele tratamentului:</label><p></p>
            <input type="text" name="nume" id="nume" class="form-input"><p></p>
            <p></p>
            <label for="id_diagnostic">Diagnosticul</label><p></p>
            <select name="id_diagnostic" id="id_diagnostic" class="form-input">
                <option value="">-- selectează diagnosticul --</option>
                @foreach($diagnostice as $diag)
                    <option value="{{ $diag->id }}">
                        {{ $diag->nume }} -> {{ $diag->data }} - {{ $diag->pacient->nume }} {{ $diag->pacient->prenume }}
                    </option>
                @endforeach
            </select><p></p>
            <p></p>
            <label for="data_sfarsit">Data Sfarsit</label><p></p>
            <input type="date" name="data_sfarsit" id="data_sfarsit" class="form-input"><p></p>
            <p></p>
            <label for="instructiuni">Instructiuni</label><p></p>
            <textarea name="instructiuni" id="instructiuni" class="form-input"></textarea><p></p>
            <p></p>
        <button type="submit">Salvează</button>
    </form>
</div>
</div>

<script>
    function validForm() {
        const nume = document.getElementById('nume').value;
        const id_diagnostic = document.getElementById('id_diagnostic').value;
        const data_sfarsit = document.getElementById('data_sfarsit').value;
        const instructiuni = document.getElementById('instructiuni').value;
        if (!nume){
            alert('Introducerea numelui tratamentului este obligatorie!');
            return false;
        }
        if (!id_diagnostic){
            alert('Selecteaza un diagnostic!');
            return false;
        }
        if (!data_sfarsit){
            alert('Introducerea datei sfarsitului tratamentului este obligatorie!');
            return false;
        }
        if (!instructiuni){
            alert('Introducerea instructiunilor este obligatorie!');
            return false;
        }
        return true;
    }
</script>
@endsection