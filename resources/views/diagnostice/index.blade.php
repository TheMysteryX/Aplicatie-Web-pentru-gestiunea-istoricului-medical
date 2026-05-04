<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
@extends('layouts.app')

@section('content')
<div class="top">
    <h1>Administrare</h1>
    
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
    <h2>Diagnostice</h2>
    <a href="{{ route('diagnostice.create') }}">Adaugă Diagnostic...</a>

<div class="card">
    <form method="GET" action="{{ route('diagnostice.index') }}" style="display:flex; flex-direction: row; flex-wrap:wrap; gap:10px; align-items:center;">
        <input type="text" name="nume" placeholder="Caută diagnostic" class="form-input" value="{{ request('nume') }}">

        <input type="date" name="data" value="{{ request('data') }}" class="form-input">

        <select name="sort_by" class="form-input">
            <option value="">Sortează după</option>
            <option value="nume" {{ request('sort_by')=='nume' ? 'selected' : '' }}>Nume</option>
            <option value="data" {{ request('sort_by')=='data' ? 'selected' : '' }}>Data</option>
        </select>

        <select name="direction" class="form-input">
            <option value="asc" {{ request('direction')=='asc' ? 'selected' : '' }}>Crescător</option>
            <option value="desc" {{ request('direction')=='desc' ? 'selected' : '' }}>Descrescător</option>
        </select>

        <select name="limit" class="form-input">
            <option value="10" {{ request('limit')==10 ? 'selected' : '' }}>10</option>
            <option value="50" {{ request('limit')==50 ? 'selected' : '' }}>50</option>
            <option value="100" {{ request('limit')==100 ? 'selected' : '' }}>100</option>
        </select>

        <button type="submit">Aplică filtre</button>
    </form>
    <div>
        Indexare:
        @foreach(range('A','Z') as $lit)
            <a href="{{ route('diagnostice.index', array_merge(request()->all(), ['litera'=>$lit])) }}">
                {{ $lit }}
            </a>
        @endforeach
    </div>
</div>

    <table  cellpadding="10" cellspacing="5" width="100%">
        <thead>
            <tr>
                <th>Data</th>
                <th>Pacient</th>
                <th>Diagnostic</th>
                <th>Descriere</th>
                <th>Actiuni</th>
            </tr>
        </thead>
        <tbody>
            @forelse($diagnostice as $diag)
                <tr>
                    <td>{{ \Carbon\Carbon::parse($diag->data)->format('d.m.Y H:i') }}</td>
                    <td>{{ $diag->pacient->nume }} {{ $diag->pacient->prenume }}</td>
                    <td><b>{{ $diag->nume }}</b></td>
                    <td>{{ $diag->descriere }}</td>
                    <td style="white-space: nowrap;">
                        <a href="{{ route('diagnostice.edit', $diag->id) }}" class="btn-icon edit" title="Editează"><i class="fas fa-pen"></i></a>
                        <form action="{{ route('diagnostice.destroy', $diag->id) }}" method="POST" style="display:inline">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn-icon del" title="Șterge"onclick="return confirm('Confirmati stergerea?')"><i class="fas fa-times"></i>
                            </button>
                        </form>
                    </td>                   
                </tr>
            @empty
                <tr>
                    <td colspan="5">Nu există diagnostice.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
    <div style="margin-top:10px; display:flex; flex-direction: column; align-items: center; padding:10px;">
        {{ $diagnostice->appends(request()->query())->links('pagination::bootstrap-5') }}
</div>
</div>
</div>
@endsection
