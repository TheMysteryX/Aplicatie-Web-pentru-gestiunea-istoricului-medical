@extends('layouts.app')

@section('content')
<div class="top">
    <div class="titlu">
        <h1>Adaugă Programare</h1>
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
        <form method="POST" action="{{ route('programari.store') }}" onsubmit="return validForm();">
            @csrf

            @if($solicitareId)
            <input type="hidden" name="solicitare_id" value="{{ $solicitareId }}">
            @endif

            <label for="id_pacient">Pacient:</label><p></p>
            <select name="id_pacient" id="id_pacient" class="form-input"><p></p>
                <option value="">Alege pacient...</option>
                @foreach($pacienti as $pacient)

                <option value="{{ $pacient->id }}"
                    {{ (isset($pacientSelectat) && $pacientSelectat == $pacient->id) ? 'selected' : '' }}>

                    {{ $pacient->nume }} {{ $pacient->prenume }}

                </option>
                @endforeach
            </select><p></p>
            <p></p>
            <label for="data">Data și ora programării:</label><p></p>
            <input type="datetime-local" name="data" id="data" class="form-input"><p></p>
            <p></p>
            <label for="detalii">Detalii:</label><p></p>
            <textarea name="detalii" id="detalii" class="form-input"></textarea><p></p>
            <p></p>
            <button type="submit">Salvează Programarea</button>
        </form>
    </div>
</div>

<script>
    function validForm() {
        const id_pacient = document.getElementById('id_pacient').value;
        const data = document.getElementById('data').value;
        const detalii = document.getElementById('detalii').value;
        if (!id_pacient){
            alert('Selecteaza un pacient!');
            return false;
        }
        if (!data){
            alert('Introducerea datei și orei este obligatorie!');
            return false;
        }

        if (!detalii){
            alert('Introducerea detaliilor este obligatorie!');
            return false;
        }
        return true;
    }
</script> 
@endsection
