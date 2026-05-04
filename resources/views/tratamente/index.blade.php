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
    <h2>Tratamente</h2>
    <a href="{{ route('tratamente.create') }}">Adaugă tratament...</a>

    <div class="card">
        <form method="GET" action="{{ route('tratamente.index') }}" style="display:flex; flex-direction: row; flex-wrap:wrap; gap:10px; align-items:center;">
        <input type="text" name="search" placeholder="Caută..." value="{{ request('search') }}" class="form-input">
    dupa:
        <select name="search_field" class="form-input">
            <option value="nume" {{ request('search_field')=='nume' ? 'selected' : '' }}>Nume</option>
            <option value="diagnostic" {{ request('search_field')=='diagnostic' ? 'selected' : '' }}>Diagnostic</option>
        </select>
    Data Inceput:
            <input type="date" name="data_emitere" value="{{ request('data_inceput') }}" class="form-input">
    Data Sfarsit:
            <input type="date" name="data_expirare" value="{{ request('data_sfarsit') }}" class="form-input">

            <select name="sort_by" class="form-input">
                <option value="">Sortează după</option>
                <option value="nume" {{ request('sort_by')=='nume' ? 'selected' : '' }}>Nume</option>
                <option value="diagnostic" {{ request('sort_by')=='diagnostic' ? 'selected' : '' }}>Diagnostic</option>
                <option value="data_inceput" {{ request('sort_by')=='data_inceput' ? 'selected' : '' }}>Data inceput</option>
                <option value="data_sfarsit" {{ request('sort_by')=='data_sfarsits' ? 'selected' : '' }}>Data sfarsit</option>
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

    <table cellspacing="10" cellpadding="5">
            <tr>
                <th>Nume</th>
                <th>Instructiuni</th>
                <th>Diagnostic</th>
                <th>Data Inceput</th>
                <th>Data Sfarsit</th>
            </tr>
            @foreach($tratamente as $tratament)
                <tr>
                    <td>{{ $tratament->nume }}</td>
                    <td>{{ $tratament->instructiuni }}</td>
                    <td>{{ $tratament->diagnostic->nume }}</td>
                    <td>{{ $tratament->data_inceput }}</td>
                    <td>{{ $tratament->data_sfarsit }}</td>
                        <td style="white-space: nowrap;">
                        <a href="{{ route('tratamente.edit', $tratament->id) }}" class="btn-icon edit" title="Editează"><i class="fas fa-pen"></i></a>
                        <form action="{{ route('tratamente.destroy', $tratament->id) }}" method="POST" style="display:inline">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn-icon del" title="Șterge"onclick="return confirm('Confirmati stergerea?')"><i class="fas fa-times"></i>
                            </button>
                        </form>
                    </td>
                </tr>
            @endforeach
    </table>
</div>

</div>
</div>
@endsection
