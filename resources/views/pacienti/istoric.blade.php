<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

@extends('layouts.app')

@section('content')
<div class="top">
    <div class="titlu">
        <h1>Istoric</h1>
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
        <ul class="nav nav-tabs" id="mainTabs" role="tablist">
            <li class="nav-item"><button class="nav-link active" id="medici-tab" data-bs-toggle="tab" data-bs-target="#medici" type="button" role="tab">Medici</button></li>
            <li class="nav-item"><button class="nav-link" id="programari-tab" data-bs-toggle="tab" data-bs-target="#programari" type="button" role="tab">Programări</button></li>
            <li class="nav-item"><button class="nav-link" id="retete-tab" data-bs-toggle="tab" data-bs-target="#retete" type="button" role="tab">Rețete</button></li>
            <li class="nav-item"><button class="nav-link" id="diagnostice-tab" data-bs-toggle="tab" data-bs-target="#diagnostice" type="button" role="tab">Diagnostice</button></li>
            <li class="nav-item"><button class="nav-link" id="tratamente-tab" data-bs-toggle="tab" data-bs-target="#tratamente" type="button" role="tab">Tratamente</button></li>
            <li class="nav-item"><button class="nav-link" id="trimiteri-tab" data-bs-toggle="tab" data-bs-target="#trimiteri" type="button" role="tab">Trimiteri</button></li>
        </ul>

    <div class="tab-content p-3" id="mainTabsContent">
            <div class="tab-pane fade show active" id="medici" role="tabpanel">
    <h2>Medici</h2>
<form method="GET" action="{{ route('pacienti.istoric', $pacient) }}" 
      style="display:flex;flex-direction:row;flex-wrap:wrap;gap:10px;align-items:center;">

    <input type="text" name="search" placeholder="Caută..." value="{{ request('search') }}" class="form-input">
dupa:
    <select name="search_field" class="form-input">
        <option value="nume" {{ request('search_field')=='nume' ? 'selected' : '' }}>Nume</option>
        <option value="prenume" {{ request('search_field')=='prenume' ? 'selected' : '' }}>Prenume</option>
        <option value="cnp" {{ request('search_field')=='cnp' ? 'selected' : '' }}>CNP</option>
        <option value="spec" {{ request('search_field')=='spec' ? 'selected' : '' }}>Specializare</option>
    </select>
sorteaza dupa:
    <select name="sort_by" class="form-input">
        <option value="">Sortează după</option>
        <option value="nume" {{ request('sort_by')=='nume' ? 'selected' : '' }}>Nume</option>
        <option value="prenume" {{ request('sort_by')=='prenume' ? 'selected' : '' }}>Prenume</option>
        <option value="cnp" {{ request('sort_by')=='cnp' ? 'selected' : '' }}>CNP</option>
        <option value="spec" {{ request('sort_by')=='spec' ? 'selected' : '' }}>Specializare</option>
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
            <a href="{{ route('pacienti.istoric', array_merge(['pacient' => $pacient->id], request()->all(), ['litera'=>$lit])) }}">
                {{ $lit }}
            </a>
        @endforeach
    </div>

    <table cellspacing="10" cellpadding="5">
            <tr>
                <th>Nume</th>
                <th>Prenume</th>
                <th>Email</th>
                <th>Specializare</th>
                <th>CNP</th>
                <th>Adresa</th>
                <th>Telefon</th>
            </tr>
            @foreach($medici as $medic)
                <tr>
                    <td>{{ $medic->nume }}</td>
                    <td>{{ $medic->prenume }}</td>
                    <td>{{ $medic->email }}</td>
                    <td>{{ $medic->specializare->nume ?? 'N/A' }}</td>
                    <td>{{ $medic->cnp }}</td>
                    <td>{{ $medic->adresa }}</td>
                    <td>{{ $medic->telefon }}</td>
                </tr>
            @endforeach
    </table>
    <div style="margin-top:10px; display:flex; flex-direction: column; align-items: center; padding:10px;">
        {{ $medici->links('pagination::bootstrap-5') }}
    </div>  
</div>

        <div class="tab-pane fade show active" id="programari" role="tabpanel">
            <h2>Programări</h2>

            <form method="GET" action="{{ route('pacienti.istoric', $pacient) }}" 
            style="display:flex;flex-direction:row;flex-wrap:wrap;gap:10px;align-items:center;">

        Data:    <input type="date" name="data" value="{{ request('data') }}" class="form-input">

        sorteaza dupa:
            <select name="sort_by" class="form-input">
                <option value="">Sortează după</option>
                <option value="data" {{ request('sort_by')=='data' ? 'selected' : '' }}>Data</option>
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

            @if($programari->count())
                <table cellpadding="10" cellspacing="5" width="100%">
                    <thead>
                        <tr>
                            <th>Data</th>
                            <th>Status</th>
                            <th>Detalii</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($programari as $p)
                            <tr>
                                <td>{{ $p->data }}</td>
                                <td>{{ $p->status }}</td>
                                <td>{{ $p->detalii }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <div style="margin-top:10px; display:flex; flex-direction: column; align-items: center; padding:10px;">
                    {{ $programari->appends(request()->query())->links('pagination::bootstrap-5') }}
                </div>
            @else
                <p>Nicio programare găsită.</p>
            @endif
        </div>

<div class="tab-pane fade" id="retete" role="tabpanel">
    <h2>Rețete</h2>
    <form method="GET" action="{{ route('pacienti.istoric', $pacient) }}" style="display:flex; flex-direction: row; flex-wrap:wrap; gap:10px; align-items:center;">
        <input type="text" name="diagnostic" placeholder="Caută diagnostic" class="form-input" value="{{ request('diagnostic') }}">
        <input type="text" name="medic" placeholder="Caută medicul..." value="{{ request('medic') }}" class="form-input">

Data Emiterii:
        <input type="date" name="data_emitere" value="{{ request('data_emitere') }}" class="form-input">
Data Expirarii:
        <input type="date" name="data_expirare" value="{{ request('data_expirare') }}" class="form-input">

        <select name="sort_by" class="form-input">
            <option value="">Sortează după</option>
            <option value="diagnostic" {{ request('sort_by')=='diagnostic' ? 'selected' : '' }}>Diagnostic</option>
            <option value="medic" {{ request('sort_by')=='medic' ? 'selected' : '' }}>Nume medic</option>
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
            <a href="{{ route('pacienti.istoric', array_merge(['pacient' => $pacient->id], request()->all(), ['litera'=>$lit])) }}">
                {{ $lit }}
            </a>
        @endforeach
    </div>

    <table cellspacing="10" cellpadding="5">
        <tr>
            <th>Medic</th>
            <th>Diagnostic</th>
            <th>Data emiterii</th>
            <th>Data expirare</th>
            <th>Medicamente</th>
        </tr>
        @foreach($retete as $r)
            <tr>
                <td>{{ $r->medic->nume ?? '-' }} {{ $r->medic->prenume ?? '' }}</td>
                <td>{{ $r->diagnostic->nume ?? '-' }}</td>
                <td>{{ $r->data_emitere }}</td>
                <td>{{ $r->data_expirare }}</td>
                <td>{{ $r->medicamente }}</td>
            </tr>
        @endforeach
    </table>
    <div style="margin-top:10px; display:flex; flex-direction: column; align-items: center; padding:10px;">
    {{ $retete->links('pagination::bootstrap-5') }}
    </div>   
            </div>
            <div class="tab-pane fade" id="diagnostice" role="tabpanel">
    <h2>Diagnostice</h2>

   <form method="GET" action="{{ route('pacienti.istoric', $pacient) }}" style="display:flex; flex-direction: row; flex-wrap:wrap; gap:10px; align-items:center;">
        <input type="text" name="nume" placeholder="Caută diagnostic" class="form-input" value="{{ request('nume') }}">
        <input type="text" name="medic" placeholder="Caută medicul..." value="{{ request('medic') }}" class="form-input">
        <input type="date" name="data" value="{{ request('data') }}" class="form-input">

        <select name="sort_by" class="form-input">
            <option value="">Sortează după</option>
            <option value="nume" {{ request('sort_by')=='nume' ? 'selected' : '' }}>Nume</option>
            <option value="medic" {{ request('sort_by')=='medic' ? 'selected' : '' }}>Nume medic</option>
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
            <a href="{{ route('pacienti.istoric', array_merge(['pacient' => $pacient->id], request()->all(), ['litera'=>$lit])) }}">
                {{ $lit }}
            </a>
        @endforeach
    </div>

    <table cellspacing="10" cellpadding="5">
        <tr>
            <th>Medic</th>
            <th>Denumire</th>
            <th>Data</th>
            <th>Descriere</th>
        </tr>
        @foreach($diagnostice as $d)
            <tr>
                <td>{{ $d->medic->nume ?? '-' }} {{ $d->medic->prenume ?? '' }}</td>
                <td>{{ $d->nume }}</td>
                <td>{{ $d->data }}</td>
                <td>{{ $d->descriere }}</td>

            </tr>
        @endforeach
    </table>
    <div style="margin-top:10px; display:flex; flex-direction: column; align-items: center; padding:10px;">
        {{ $diagnostice->links('pagination::bootstrap-5') }}
    </div>
            </div>
            <div class="tab-pane fade" id="tratamente" role="tabpanel">
    <h2>Tratamente</h2>

        <form method="GET" action="{{ route('pacienti.istoric', $pacient) }}" style="display:flex; flex-direction: row; flex-wrap:wrap; gap:10px; align-items:center;">
        <input type="text" name="search" placeholder="Caută..." value="{{ request('search') }}" class="form-input">
    dupa:
        <select name="search_field" class="form-input">
            <option value="nume" {{ request('search_field')=='nume' ? 'selected' : '' }}>Nume tratament</option>
            <option value="diagnostic" {{ request('search_field')=='diagnostic' ? 'selected' : '' }}>Diagnostic</option>
            <option value="medic" {{ request('sort_by')=='medic' ? 'selected' : '' }}>Nume medic</option>
        </select>
    Data Inceput:
            <input type="date" name="data_emitere" value="{{ request('data_inceput') }}" class="form-input">
    Data Sfarsit:
            <input type="date" name="data_expirare" value="{{ request('data_sfarsit') }}" class="form-input">

            <select name="sort_by" class="form-input">
                <option value="">Sortează după</option>
                <option value="nume" {{ request('sort_by')=='nume' ? 'selected' : '' }}>Nume tratament</option>
                <option value="diagnostic" {{ request('sort_by')=='diagnostic' ? 'selected' : '' }}>Diagnostic</option>
                <option value="medic" {{ request('sort_by')=='medic' ? 'selected' : '' }}>Nume medic</option>
                <option value="data_inceput" {{ request('sort_by')=='data_inceput' ? 'selected' : '' }}>Data inceput</option>
                <option value="data_sfarsit" {{ request('sort_by')=='data_sfarsit' ? 'selected' : '' }}>Data sfarsit</option>
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
                <a href="{{ route('pacienti.istoric', array_merge(['pacient' => $pacient->id], request()->all(), ['litera'=>$lit])) }}">
                    {{ $lit }}
                </a>
            @endforeach
        </div>

    <table cellspacing="10" cellpadding="5">
        <tr>
            <th>Medic</th>
            <th>Nume</th>
            <th>Data Inceput</th>
            <th>Data Sfarsit</th>
            <th>Instructiuni</th>
        </tr>
        @foreach($tratamente as $t)
            <tr>
                <td>{{ $t->medic->nume ?? '-' }} {{ $t->medic->prenume ?? '-' }} </td>
                <td>{{ $t->nume }}</td>
                <td>{{ $t->data_inceput }}</td>
                <td>{{ $t->data_sfarsit }}</td>
                <td>{{ $t->instructiuni }}</td>
            </tr>
        @endforeach
    </table>
    <div style="margin-top:10px; display:flex; flex-direction: column; align-items: center; padding:10px;">
        {{ $tratamente->links('pagination::bootstrap-5') }}
    </div>
            </div>
            <div class="tab-pane fade" id="trimiteri" role="tabpanel">
    <h2>Trimiteri</h2>

<form method="GET" action="{{ route('pacienti.istoric', $pacient) }}" 
      style="display:flex;flex-direction:row;flex-wrap:wrap;gap:10px;align-items:center;">

    <input type="text" name="search" placeholder="Caută..." value="{{ request('search') }}" class="form-input">
dupa:
    <select name="search_field" class="form-input">
        <option value="titlu" {{ request('search_field')=='titlu' ? 'selected' : '' }}>Titlu</option>
        <option value="medic" {{ request('search_field')=='medic' ? 'selected' : '' }}>Nume medic</option>
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
        <option value="medic" {{ request('sort_by')=='medic' ? 'selected' : '' }}>Nume medic</option>
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
            <a href="{{ route('pacienti.istoric', array_merge(['pacient' => $pacient->id], request()->all(), ['litera'=>$lit])) }}">
                {{ $lit }}
            </a>
        @endforeach
    </div>

    <table cellspacing="10" cellpadding="5">
        <tr>
            <th>Medic</th>
            <th>Titlu</th>
            <th>Locație</th>
            <th>Data Emiterii</th>
            <th>Data Expirării</th>
            <th>Detalii</th>
        </tr>
        @foreach($trimiteri as $tr)
            <tr>
                <td>{{ $tr->medic->nume ?? '-' }} {{ $tr->medic->prenume ?? '-' }}</td>
                <td>{{ $tr->titlu }}</td>
                <td>{{ $tr->locatie }}</td>
                <td>{{ $tr->data_emitere }}</td>
                <td>{{ $tr->data_expirare }}</td>
                <td>{{ $tr->detalii }}</td>
            </tr>
        @endforeach
    </table>
    <div style="margin-top:10px; display:flex; flex-direction: column; align-items: center; padding:10px;">
        {{ $trimiteri->links('pagination::bootstrap-5') }}
    </div>
</div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
@endsection