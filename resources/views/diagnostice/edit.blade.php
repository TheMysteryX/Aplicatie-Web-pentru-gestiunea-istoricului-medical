@extends('layouts.app')

@section('content')
<div class="top">
    <h1>Editeaza Diagnostic</h1>
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
    <form method="POST" action="{{ route('diagnostice.update', $diagnostic->id) }}">
        @csrf
        @method('PUT')
            <label for="id_programare">Programarea</label>
            <select name="id_programare" id="id_programare" class="form-input">
                @foreach($programari as $prog)
                    <option value="{{ $prog->id }}">
                        {{ $diagnostic->id_programare == $prog->id ? 'Programare selectata anterior:' : '' }}
                        {{ $prog->data }} - {{ $prog->pacient->nume }} {{ $prog->pacient->prenume }}
                    </option>
                @endforeach
            </select><p></p>

            <label for="nume">Diagnostic</label>
            <input type="text" name="nume" id="nume" class="form-input" value= "{{ $diagnostic->nume }}"><p></p>

            <label for="descriere">Descriere</label>
            <textarea name="descriere" id="descriere" class="form-input">{{ old('descriere', $diagnostic->descriere) }}</textarea><p></p>

        <button type="submit">Salvează</button>
    </form>
</div>
</div>
@endsection
