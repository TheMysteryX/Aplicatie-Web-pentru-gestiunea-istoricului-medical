@extends('layouts.app')

@section('content')
<div class="top">
    <h1>Adaugă Reteta</h1>
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
    <form method="POST" action="{{ route('retete.store') }}" onsubmit="return validForm();">
        @csrf
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
            <label for="data_expirare">Data Expirarii</label><p></p>
            <input type="date" name="data_expirare" id="data_expirare" class="form-input"><p></p>
            <p></p>
            <label for="medicamente">Medicamente</label><p></p>
            <textarea name="medicamente" id="medicamente" class="form-input"></textarea><p></p>
            <p></p>
        <button type="submit">Salvează</button>
    </form>
</div>
</div>

<script>
    function validForm() {
        const id_diagnostic = document.getElementById('id_diagnostic').value;
        const data_expirare = document.getElementById('data_expirare').value;
        const medicamente = document.getElementById('medicamente').value;
        if (!id_diagnostic){
            alert('Selecteaza un diagnostic!');
            return false;
        }
        if (!data_expirare){
            alert('Introducerea datei expirarii este obligatorie!');
            return false;
        }
        if (!medicamente){
            alert('Introducerea medicamentelor este obligatorie!');
            return false;
        }
        return true;
    }
@endsection