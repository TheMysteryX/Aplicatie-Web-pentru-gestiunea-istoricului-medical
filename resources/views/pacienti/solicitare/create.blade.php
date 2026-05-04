@extends('layouts.app')

@section('content')
<div class="top">
    <div class="titlu">
        <h1>Solicitare programare</h1>
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
    <form method="POST" action="{{ route('solicitare.store') }}">
    @csrf

    <label>Medic:</label><p></p>
    <select name="medic_id" class="form-input">
        @foreach($medici as $medic)
            <option value="{{ $medic->id }}">
                {{ $medic->nume }}
            </option>
        @endforeach
    </select>
    <p></p>
    <label>Data început:</label><p></p>
    <input type="date" name="data_start" class="form-input"><p></p>
    <p></p>
    <label>Data sfârșit:</label><p></p>
    <input type="date" name="data_end" class="form-input"><p></p>
    <p></p>
    <label>Mesaj:</label><p></p>
    <textarea name="mesaj" class="form-input"></textarea><p></p>
    <p></p>
    <button type="submit">Trimite solicitare</button>
    </form>
</div>
</div>
@endsection