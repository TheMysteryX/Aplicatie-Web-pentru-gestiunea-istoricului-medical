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
    <h2>Retete</h2>
    <a href="{{ route('retete.create') }}">Adaugă o reteta..</a>

<div class="card">
    <form method="GET" action="{{ route('retete.index') }}" style="display:flex; flex-direction: row; flex-wrap:wrap; gap:10px; align-items:center;">
        <input type="text" name="diagnostic" placeholder="Caută diagnostic" class="form-input"
               value="{{ request('diagnostic') }}">
Data Emiterii:
        <input type="date" name="data_emitere" value="{{ request('data_emitere') }}" class="form-input">
Data Expirarii:
        <input type="date" name="data_expirare" value="{{ request('data_expirare') }}" class="form-input">

        <select name="sort_by" class="form-input">
            <option value="">Sortează după</option>
            <option value="diagnostic" {{ request('sort_by')=='diagnostic' ? 'selected' : '' }}>Diagnostic</option>
            <option value="data_emitere" {{ request('sort_by')=='data_emitere' ? 'selected' : '' }}>Data emiterii</option>
            <option value="data_expirare" {{ request('sort_by')=='data_expirare' ? 'selected' : '' }}>Data expirării</option>
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
            <a href="{{ route('retete.index', array_merge(request()->all(), ['litera'=>$lit])) }}">
                {{ $lit }}
            </a>
        @endforeach
    </div>
</div>

<div class="card">
    <table cellspacing="10" cellpadding="5">
        <tr>
            <th>Diagnostic</th>
            <th>Data Emiterii</th>
            <th>Data Expirarii</th>
            <th>Medicamente</th>
            <th>Acțiuni</th>
        </tr>
        @foreach($retete as $reteta)
            <tr>
                <td>{{ $reteta->diagnostic->nume }}</td>
                <td>{{ $reteta->data_emitere }}</td>
                <td>{{ $reteta->data_expirare }}</td>
                <td>{{ $reteta->medicamente }}</td>
                    <td style="white-space: nowrap;">
                        <a href="{{ route('retete.edit', $reteta->id) }}" class="btn-icon edit" title="Editează"><i class="fas fa-pen"></i></a>
                        <form action="{{ route('retete.destroy', $reteta->id) }}" method="POST" style="display:inline">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn-icon del" title="Șterge"onclick="return confirm('Confirmati stergerea?')"><i class="fas fa-times"></i>
                            </button>
                        </form>
                    </td>
            </tr>
        @endforeach
    </table>

    <div style="margin-top:10px; display:flex; flex-direction: column; align-items: center; padding:10px;">
        {{ $retete->appends(request()->query())->links('pagination::bootstrap-5') }}
    </div>
</div>

</div>

</div>
</div>
@endsection