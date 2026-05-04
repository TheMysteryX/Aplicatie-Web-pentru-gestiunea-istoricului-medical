@extends('layouts.app')

@section('content')
<div class="top">
    <h1>Adaugă un pacient existent</h1>
</div>

<div class="card">

    <form method="GET" action="{{ route('pacienti.existent') }}">
        <input type="text" name="search" placeholder="Caută după nume, prenume sau CNP" value="{{ request('search') }}"><p></p>
        <button type="submit">Caută</button><p></p>
    </form>

    <table>
        <tr>
            <th>Nume</th>
            <th>Prenume</th>
            <th>CNP</th>
            <th>Acțiune</th>
        </tr>

        @foreach($pacienti as $pacient)
            <tr>
                <td>{{ $pacient->nume }}</td>
                <td>{{ $pacient->prenume }}</td>
                <td>{{ $pacient->cnp }}</td>
                <td>
                    <form method="POST"
                          action="{{ route('pacienti.existent.store', $pacient->id) }}">
                        @csrf
                        <button type="submit">
                            Adaugă în lista mea
                        </button>
                    </form>
                </td>
            </tr>
        @endforeach
    </table>

    <div style="margin-top:15px;">
        {{ $pacienti->links('pagination::bootstrap-5') }}
    </div>

</div>
@endsection