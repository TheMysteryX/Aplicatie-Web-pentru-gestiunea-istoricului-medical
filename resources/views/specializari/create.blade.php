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
        <h2 align = 'center'>Creeare Specializare</h2>
        <form method="POST" action="{{ route('specializari.store') }}"onsubmit="return validForm();">
            @csrf
            <label>Denumire:</label><p></p>
            <input type="text" class="form-input" name="nume" id="nume"><p></p>
            <p></p>
            <button type="submit">Salveaza</button>

        </form>
    </div>
</div>

<script>
function validForm() {
    const nume = document.getElementById('nume').value.trim();
    if (!nume) {
        alert("Introduceti denumirea specializarii!");
        return false;
    }
    return true;
}
</script>
@endsection