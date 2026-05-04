<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

@extends('layouts.app')

@section('content')
<div class="top">
    <div class="titlu">
        <h1>Profil medic: Dr. {{ $medic->nume }} {{ $medic->prenume }}</h1>
    </div>
</div>

<div class="middle">
    <div class="card">
            <h2>Date personale</h2>
            <p><strong>Data nașterii:</strong> {{ $medic->data_nasterii }}</p>
            <p><strong>CNP:</strong> {{ $medic->cnp }}</p>
            <p><strong>Telefon:</strong> {{ $medic->telefon }}</p>
            <p><strong>Adresă:</strong> {{ $medic->adresa }}</p>
            <p><strong>Asigurat:</strong> {{ $medic['asigurat/a'] ? 'Da' : 'Nu' }}</p>
    </div>
    <div class="card">
        <ul class="nav nav-tabs" id="pacientTabs" role="tablist">
            <li class="nav-item"><button class="nav-link active" id="programari-tab" data-bs-toggle="tab" data-bs-target="#programari" type="button" role="tab">Programări</button></li>
            <li class="nav-item"><button class="nav-link" id="retete-tab" data-bs-toggle="tab" data-bs-target="#retete" type="button" role="tab">Rețete</button></li>
            <li class="nav-item"><button class="nav-link" id="diagnostice-tab" data-bs-toggle="tab" data-bs-target="#diagnostice" type="button" role="tab">Diagnostice</button></li>
            <li class="nav-item"><button class="nav-link" id="tratamente-tab" data-bs-toggle="tab" data-bs-target="#tratamente" type="button" role="tab">Tratamente</button></li>
            <li class="nav-item"><button class="nav-link" id="trimiteri-tab" data-bs-toggle="tab" data-bs-target="#trimiteri" type="button" role="tab">Trimiteri</button></li>
        </ul>

        <div class="tab-content" id="pacientTabsContent">
        <div class="tab-pane fade show active" id="programari" role="tabpanel">
            <h2>Programări</h2>

            <form method="GET" action="{{ route('medici.show', $medic->id) }}" 
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

    <form method="GET" action="{{ route('medici.show', $medic->id) }}" style="display:flex; flex-direction: row; flex-wrap:wrap; gap:10px; align-items:center;">
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
            <a href="{{ route('medici.show', array_merge(request()->all(), ['litera'=>$lit, 'medici' => $medic->id])) }}">
                {{ $lit }}
            </a>
        @endforeach
    </div>
        @if($retete->count())
            <table cellpadding="10" cellspacing="5" width="100%">
                <thead>
                    <tr>
                        <th>Diagnostic</th>
                        <th>Data Emiterii</th>
                        <th>Data expirării</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($retete as $r)
                        <tr>
                            <td>{{ $r->diagnostic->nume ?? '-' }}</td>
                            <td>{{ $r->data_emitere }}</td>
                            <td>{{ $r->data_expirare }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div style="margin-top:10px; display:flex; flex-direction: column; align-items: center; padding:10px;">
                {{ $retete->appends(request()->query())->links('pagination::bootstrap-5') }}
            </div>            
        @else
            <p>Nicio rețetă emisă.</p>
        @endif
        </div>

        <div class="tab-pane fade" id="diagnostice" role="tabpanel">
        <h2>Diagnostice</h2>

   <form method="GET" action="{{ route('medici.show', $medic->id) }}" style="display:flex; flex-direction: row; flex-wrap:wrap; gap:10px; align-items:center;">
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
            <a href="{{ route('medici.show', array_merge(request()->all(), ['litera'=>$lit, 'medici' => $medic->id])) }}">
                {{ $lit }}
            </a>
        @endforeach
    </div>

        @if($diagnostice->count())
            <table cellpadding="10" cellspacing="5" width="100%">
                <thead>
                    <tr>
                        <th>Data</th>
                        <th>Diagnostic</th>
                        <th>Descriere</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($diagnostice as $d)                    
                        <tr>
                            <td>{{ $d->data }}</td>
                            <td>{{ $d->nume }}</td> 
                            <td>{{ $d->descriere }}</td> 
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div style="margin-top:10px; display:flex; flex-direction: column; align-items: center; padding:10px;">
                {{ $diagnostice->appends(request()->query())->links('pagination::bootstrap-5') }}
            </div>            
        @else
            <p>Niciun diagnostic înregistrat.</p>
        @endif
        </div>

        <div class="tab-pane fade" id="tratamente" role="tabpanel">
        <h2>Tratamente</h2>

        <form method="GET" action="{{ route('medici.show', $medic->id) }}" style="display:flex; flex-direction: row; flex-wrap:wrap; gap:10px; align-items:center;">
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
                <a href="{{ route('medici.show', array_merge(request()->all(), ['litera'=>$lit, 'medici' => $medic->id])) }}">
                    {{ $lit }}
                </a>
            @endforeach
        </div>

        @if($tratamente->count())
            <table cellpadding="10" cellspacing="5" width="100%">
                <thead>
                    <tr>
                        <th>Nume</th>
                        <th>Diagnostic</th>
                        <th>Data Inceput</th>
                        <th>Data Sfarsit</th>
                        <th>Instructiuni</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($tratamente as $t)
                        <tr>
                            <td>{{ $t->nume }}</td>
                            <td>{{ $t->diagnostic->nume }}</td>
                            <td>{{ $t->data_inceput }}</td>
                            <td>{{ $t->data_sfarsit }}</td>                            
                            <td>{{ $t->instructiuni }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div style="margin-top:10px; display:flex; flex-direction: column; align-items: center; padding:10px;">
                {{ $tratamente->appends(request()->query())->links('pagination::bootstrap-5') }}
            </div>            
        @else
            <p>Niciun tratament asociat.</p>
        @endif
        </div>

        <div class="tab-pane fade" id="trimiteri" role="tabpanel">
        <h2>Trimiteri</h2>

<form method="GET" action="{{ route('medici.show', $medic->id) }}" 
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
            <a href="{{ route('medici.show', array_merge(request()->all(), ['litera'=>$lit, 'medici' => $medic->id])) }}">
                {{ $lit }}
            </a>
        @endforeach
    </div>

        @if($trimiteri->count())
            <table cellpadding="10" cellspacing="5" width="100%">
                <thead>
                    <tr>
                        <th>Titlu</th>
                        <th>Data Emiterii
                        <th>Data Expirarii</th>
                        <th>Locatie</th>
                        <th>Detalii</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($trimiteri as $tr)
                        <tr>
                            <td>{{ $tr->titlu }}</td>
                            <td>{{ $tr->data_emitere }}</td>
                            <td>{{ $tr->data_expirare }}</td>
                            <td>{{ $tr->locatie }}</td>
                            <td>{{ $tr->detalii }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div style="margin-top:10px; display:flex; flex-direction: column; align-items: center; padding:10px;">
                {{ $trimiteri->appends(request()->query())->links('pagination::bootstrap-5') }}
            </div>            
        @else
            <p>Nicio trimitere disponibilă.</p>
        @endif
    </div>
    </div>
</div>
</div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
@endsection
