@extends('layouts.app')

@section('content')
<div class="top">
    <div class="titlu">
        <h1>Contul meu</h1>
    </div>
</div>
<div class="middle">

    @if (session('success'))
        <div class="card" style="color:green;">{{ session('success') }}</div>
    @endif

    <div class="card">
        <h2>Informații cont</h2>
        <p><strong>Nume:</strong> {{ $user->nume }}</p>
        <p><strong>Prenume:</strong> {{ $user->prenume }}</p>
        <p><strong>Email:</strong> {{ $user->email }}</p>
        <p><strong>Telefon:</strong> {{ $user->telefon }}</p>
        <p><strong>Adresă:</strong> {{ $user->adresa }}</p>
        <p><strong>Rol:</strong> {{ ucfirst($user->rol) }}</p>
        @if($user->specializare)
            <p><strong>Specializare:</strong> {{ $user->specializare->nume }}</p>
        @endif
    </div>

    <div class="buttons" style="padding-bottom:30px;" align='center'">
        <a href="{{ route('cont.edit') }}" class="btn" style="font-size:small;padding-top:5px;">Editează datele</a>

        <form action="{{ route('logout') }}" method="POST" style="display:inline">
            @csrf
            <button type="submit" class="btn logout">Delogare</button>
        </form>

        <form action="{{ route('cont.delete') }}" method="POST" style="display:inline" 
              onsubmit="return confirm('Sigur vrei să ștergi contul? Această acțiune este ireversibilă.')">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn delete">Șterge contul</button>
        </form>
    </div>
</div>
@endsection
