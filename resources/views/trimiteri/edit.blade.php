@extends('layouts.app')

@section('content')
<div class="top">
    <h1>Editeaza Trimitere</h1>
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
    <form method="POST" action="{{ route('trimiteri.update', $trimitere->id) }}" onsubmit="return validForm();">
        @csrf
        @method('PUT')
            <label for="id_programare">Programarea</label><p></p>
            <select name="id_programare" id="id_programare" class="form-input"><p></p>
                <option value="">-- selectează programarea --</option>
                @foreach($programari as $prog)
                    <option value="{{ $prog->id }}">
                        {{ $prog->data }} - {{ $prog->pacient->nume }} {{ $prog->pacient->prenume }}
                    </option>
                @endforeach
            </select><p></p>
            <p></p>
            <label for="titlu">Titlu</label><p></p>
            <input type="text" name="titlu" id="titlu" class="form-input" value='{{ $trimitere->titlu }}'><p></p>
            <p></p>
            <label for="detalii">Detalii</label><p></p>
            <textarea name="detalii" id="detalii" class="form-input" {{ old('detalii', $trimitere->detalii) }}></textarea><p></p>
            <p></p>
            <label for="locatie">Locatie</label><p></p>
            <textarea name="locatie" id="locatie" class="form-input" value='{{ $trimitere->locatie }}'></textarea><p></p>
            <p></p>
            <label for="data_expirare">Data Expirarii</label><p></p>
            <input type="date" name="data_expirare" id="data_expirare" class="form-input" value='{{ $trimitere->data_expirare }}'><p></p>            
            <p></p>
        <button type="submit">Salvează</button>
    </form>
</div>
</div>

<script>
    function validForm() {
        const id_programare = document.getElementById('id_programare').value;
        const titlu = document.getElementById('titlu').value;
        const detalii = document.getElementById('detalii').value;
        const locatie = document.getElementById('locatie').value;
        const data_expirare = document.getElementById('data_expirare').value;

        if (!id_programare){
            alert('Selecteaza o programare!');
            return false;
        }
        if (!titlu){
            alert('Introducerea titlului este obligatorie!');
            return false;
        }
        if (!detalii){
            alert('Introducerea detaliilor este obligatorie!');
            return false;
        }
        if (!locatie){
            alert('Introducerea locatia este obligatorie!');
            return false;
        }
        if (!data_expirare){
            alert('Introducerea datei expirarii este obligatorie!');
            return false;
        }

        return true;
    }
</script>
@endsection