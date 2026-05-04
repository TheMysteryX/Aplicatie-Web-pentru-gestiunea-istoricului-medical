@extends('layouts.app')

@section('content')
<div class="top">
    <div class="titlu">
        <h1>Editeaza Programarea</h1>
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
        <form method="POST" action="{{ route('programari.update', $programare->id) }}" onsubmit="return validForm();">
            @csrf
            @method('PUT')

            <label for="id_pacient">Pacient:</label><p></p>
            <select name="id_pacient" id="id_pacient" class="form-input" value="{{ $programare->id_pacient }}">
                <option value="">Alege pacient...</option>
                @foreach($pacienti as $pacient)
                    <option value="{{ $pacient->id }}">
                        {{ $pacient->nume }} {{ $pacient->prenume }}
                    </option>
                @endforeach
            </select><p></p>
            <p></p>
            <label for="data">Data și ora programării:</label><p></p>
            <input type="datetime-local" name="data" id="data" class="form-input" value="{{ $programare->data }}"><p></p>
            <p></p>
            <label for="status">Status:</label><p></p>
            <select name="status" id="status" class="form-input">
                <option value="viitoare" {{ $programare->status == 'viitoare' ? 'selected' : '' }}>Viitoare</option>
                <option value="finalizata" {{ $programare->status == 'finalizata' ? 'selected' : '' }}>Finalizată</option>
                <option value="amanata" {{ $programare->status == 'amanata' ? 'selected' : '' }}>Amânată</option>
            </select><p></p>
            <p></p>
            <label for="detalii">Detalii:</label><p></p>
            <textarea name="detalii" id="detalii" class="form-input" value="{{ $programare->detalii }}"></textarea><p></p>
            <p></p>
            <button type="submit">Salvează Programarea</button>
        </form>
    </div>
</div>

<script>
    function validForm() {
        const id_pacient = document.getElementById('id_pacient').value;
        const data = document.getElementById('data').value;
        const status = document.getElementById('status').value;
        const detalii = document.getElementById('detalii').value;
        if (!id_pacient){
            alert('Selecteaza un pacient!');
            return false;
        }
        if (!data){
            alert('Introducerea datei și orei este obligatorie!');
            return false;
        }
        if (!status){
            alert('Selecteaza un status!');
            return false;
        }
        if (status === 'finalizata' && new Date(data) > new Date()){
            alert('O programare viitoare nu poate fi marcată ca finalizată!');
            return false;
        }
        if(status === 'viitoare' && new Date(data) < new Date()){
            alert('O programare din trecut nu poate fi marcată ca viitoare!');
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