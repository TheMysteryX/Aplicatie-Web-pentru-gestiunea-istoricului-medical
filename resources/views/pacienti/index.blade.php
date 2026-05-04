<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
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
    <h2>Pacienti</h2>
    <a href="{{ route('pacienti.create') }}">Adaugă un pacient nou</a>
    <a href="{{ route('pacienti.existent') }}">Adaugă un pacient existent</a>


<form method="GET" action="{{ route('pacienti.index') }}" 
      style="display:flex;flex-direction:row;flex-wrap:wrap;gap:10px;align-items:center;">

    <input type="text" name="search" placeholder="Caută..." value="{{ request('search') }}" class="form-input">
dupa:
    <select name="search_field" class="form-input">
        <option value="nume" {{ request('search_field')=='nume' ? 'selected' : '' }}>Nume</option>
        <option value="prenume" {{ request('search_field')=='prenume' ? 'selected' : '' }}>Prenume</option>
        <option value="cnp" {{ request('search_field')=='cnp' ? 'selected' : '' }}>CNP</option>
    </select>
sorteaza dupa:
    <select name="sort_by" class="form-input">
        <option value="">Sortează după</option>
        <option value="nume" {{ request('sort_by')=='nume' ? 'selected' : '' }}>Nume</option>
        <option value="prenume" {{ request('sort_by')=='prenume' ? 'selected' : '' }}>Prenume</option>
        <option value="data_nasterii" {{ request('sort_by')=='data_nasterii' ? 'selected' : '' }}>Data nașterii</option>
    </select>

    <select name="direction" class="form-input">
        <option value="asc" {{ request('direction')=='asc' ? 'selected' : '' }}>Crescător</option>
        <option value="desc" {{ request('direction')=='desc' ? 'selected' : '' }}>Descrescător</option>
    </select>

    <select name="limit" class="form-input">
        <option value="5" {{ request('limit')==5 ? 'selected' : '' }}>5</option>
        <option value="10" {{ request('limit')==10 ? 'selected' : '' }}>10</option>
        <option value="50" {{ request('limit')==50 ? 'selected' : '' }}>50</option>
        <option value="100" {{ request('limit')==100 ? 'selected' : '' }}>100</option>
    </select>

    <button type="submit">Aplică filtre</button>
</form>
    <div>
        Indexare:
        @foreach(range('A','Z') as $lit)
            <a href="{{ route('pacienti.index', array_merge(request()->all(), ['litera'=>$lit])) }}">
                {{ $lit }}
            </a>
        @endforeach
    </div>

    <table cellspacing="10" cellpadding="5">
            <tr>
                <th>Nume</th>
                <th>Prenume</th>
                <th>Data Nasterii</th>
                <th>CNP</th>
                <th>Adresa</th>
                <th>Telefon</th>
                <th>Asigurat/a</th>
                <th>Acțiuni</th>
            </tr>
            @foreach($pacienti as $pacient)
                <tr>
                    <td>{{ $pacient->nume }}</td>
                    <td>{{ $pacient->prenume }}</td>
                    <td>{{ $pacient->data_nasterii }}</td>
                    <td>{{ $pacient->cnp }}</td>
                    <td>{{ $pacient->adresa }}</td>
                    <td>{{ $pacient->telefon }}</td>
                    <td>{{ $pacient['asigurat/a'] ? 'Da' : 'Nu' }}</td>
                    <td style="white-space: nowrap;">
                        <a href="{{ route('pacienti.show', $pacient->id) }}" class="btn-icon profil" title="Profil"><i class="fas fa-user"></i></a>
                        <a href="{{ route('pacienti.edit', $pacient->id) }}" class="btn-icon edit" title="Editează"><i class="fas fa-pen"></i></a>
                        <form action="{{ route('pacienti.destroy', $pacient->id) }}" method="POST" style="display:inline">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn-icon del" title="Șterge"onclick="return confirm('Confirmati stergerea?')"><i class="fas fa-times"></i>
                            </button>
                        </form>
                    </td>
                </tr>
            @endforeach
    </table>
    <div style="margin-top:10px; display:flex; flex-direction: column; align-items: center; padding:10px;">
        {{ $pacienti->appends(request()->query())->links('pagination::bootstrap-5') }}
    </div>    
</div>
</div>
</div>
@endsection
