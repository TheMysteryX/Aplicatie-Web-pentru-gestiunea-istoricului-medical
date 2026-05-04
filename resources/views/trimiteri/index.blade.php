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
    <h2>Trimiteri</h2>
    <a href="{{ route('trimiteri.create') }}">Adaugă trimitere...</a>

<div class="card">
    <form method="GET" action="{{ route('trimiteri.index') }}" 
        style="display:flex;flex-direction:row;flex-wrap:wrap;gap:10px;align-items:center;">

        <input type="text" name="search" placeholder="Caută..." value="{{ request('search') }}" class="form-input">
    dupa:
        <select name="search_field" class="form-input">
            <option value="titlu" {{ request('search_field')=='titlu' ? 'selected' : '' }}>Titlu</option>
            <option value="locatie" {{ request('search_field')=='locatie' ? 'selected' : '' }}>Locatie</option>
        </select>
    Data Emiterii:
        <input type="date" name="data_emitere" value="{{ request('data_emitere') }}" class="form-input">
    Data Expirarii:
        <input type="date" name="data_expirare" value="{{ request('data_expirare') }}" class="form-input">
    sorteaza dupa:
        <select name="sort_by" class="form-input">
            <option value="">Sortează după</option>
            <option value="titlu" {{ request('sort_by')=='titlu' ? 'selected' : '' }}>Titlu</option>
            <option value="locatie" {{ request('sort_by')=='locatie' ? 'selected' : '' }}>Locatie</option>
            <option value="data_emitere" {{ request('sort_by')=='data_emitere' ? 'selected' : '' }}>Data emiterii</option>
            <option value="data_expirare" {{ request('sort_by')=='data_expirare' ? 'selected' : '' }}>Data expirării</option>        
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
                <a href="{{ route('trimiteri.index', array_merge(request()->all(), ['litera'=>$lit])) }}">
                    {{ $lit }}
                </a>
            @endforeach
        </div>
</div>

    <table cellspacing="10" cellpadding="5">
            <tr> 
                <th>Titlu</th>
                <th>Detalii</th>
                <th>Locatie</th>
                <th>Data Emiterii</th>
                <th>Data Expirarii</th>
                <th>Acțiuni</th>
            </tr>
            @foreach($trimiteri as $trimitere)
                <tr>
                    <td>{{ $trimitere->titlu }}</td>
                    <td>{{ $trimitere->detalii }}</td>
                    <td>{{ $trimitere->locatie }}</td>
                    <td>{{ $trimitere->data_emitere }}</td>
                    <td>{{ $trimitere->data_expirare }}</td>
                    <td style="white-space: nowrap;">
                        <a href="{{ route('trimiteri.edit', $trimitere->id) }}" class="btn-icon edit" title="Editează"><i class="fas fa-pen"></i></a>
                        <form action="{{ route('trimiteri.destroy', $trimitere->id) }}" method="POST" style="display:inline">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn-icon del" title="Șterge"onclick="return confirm('Confirmati stergerea?')"><i class="fas fa-times"></i>
                            </button>
                        </form>
                    </td>
                </tr>
            @endforeach
    </table>
<div style="margin-top:10px; display:flex; flex-direction: column; align-items: center; padding:10px;">
    {{ $trimiteri->appends(request()->query())->links('pagination::bootstrap-5') }}
</div>

</div>
</div>
@endsection