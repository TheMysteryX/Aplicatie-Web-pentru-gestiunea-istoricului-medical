@extends('layouts.app')

@section('content')
<div class="top">
    <div class="titlu">
        <h1>Editeaza Specializarea</h1>
    </div>
</div>

<div class="middle">
    <div class="card">
        <form method="POST" action="{{ route('specializari.update', $specializare->id) }}" onsubmit="return validForm();">
            @csrf
            @method('PUT')

            <label for="nume">Denumire:</label><p></p>
            <input type="text" name="nume" id="nume" class="form-input" value="{{ $specializare->nume }}"><p></p>
            <p></p>
            <button type="submit">Salveaza modificarile</button>
        </form>
    </div>
</div>

<script>
function validForm() {
    const nume = document.getElementById('nume').value.trim();
    if (!nume) {
        alert("Introduceti numele specializarii!");
        return false;
    }
    return true;
}
</script>
@endsection