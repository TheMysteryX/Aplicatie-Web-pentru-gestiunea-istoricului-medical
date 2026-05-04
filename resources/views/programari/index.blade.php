<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

@extends('layouts.app')

@section('content')
<div class="top">
    <div class="titlu">
        <h1>Programări</h1>
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

    <div class="card" style="margin-bottom:20px;">
        <h2>Calendar Programări</h2>
        <div id="calendar"></div> <p></p>
        <a href="{{ route('programari.create') }}">Adaugă programare...</a>

    <h3>Legendă Status Programări</h3><p></p>
    <ul style="list-style:none; padding-left:0;">
        <li>
            <span style="display:inline-block; width:15px; height:15px; background-color:#4aa8e7; margin-right:8px; border-radius: 15px;"></span>
            Viitoare
        </li>
        <li>
            <span style="display:inline-block; width:15px; height:15px; background-color:#8eab8e; margin-right:8px; border-radius: 15px;"></span>
            Finalizată
        </li>
        <li>
            <span style="display:inline-block; width:15px; height:15px; background-color:#f0ac4edf; margin-right:8px; border-radius: 15px;"></span>
            Amânată
        </li>
    </ul>
    </div>

    <div class="card" style="margin-bottom:20px;">
    <h2>Filtrează Programările...</h2>
        <form method="GET" action="{{ route('programari.index') }}" 
        style="display:flex;flex-direction:row;flex-wrap:wrap;gap:10px;align-items:center;">

        <input type="text" name="nume" placeholder="Caută..." value="{{ request('nume') }}" class="form-input">
        <input type="date" name="data" value="{{ request('data') }}" class="form-input">

    sorteaza dupa:
        <select name="sort_by" class="form-input">
            <option value="">Sortează după</option>
            <option value="nume" {{ request('sort_by')=='nume' ? 'selected' : '' }}>Nume</option>
            <option value="data" {{ request('sort_by')=='data' ? 'selected' : '' }}>Data nașterii</option>
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
    </div>

<div class="card" style="margin-bottom:20px;">
    <h2>Programări viitoare</h2>

    <table cellspacing="10" cellpadding="5">
        <tr>
            <th>Data</th>
            <th>Pacient</th>
            <th>Detalii</th>
            <th>Acțiuni</th>
        </tr>
        
        @forelse($programariViitoare as $prog)
            <tr>
                <td>{{ $prog->data->format('d.m.Y, H:i') }}</td>
                <td>{{ $prog->pacient->nume }} {{ $prog->pacient->prenume }}</td> 
                <td><em>{{ $prog->detalii }}</em></td>
<td style="white-space: nowrap;">

    {{-- Finalizare --}}
    <form action="{{ route('programari.update', $prog->id) }}" method="POST" style="display:inline">
        @csrf
        @method('PUT')
        <input type="hidden" name="status" value="finalizata"><button type="submit" class="btn-icon finalize" title="Finalizează"><i class="fas fa-check"></i></button>
    </form>

    <form action="{{ route('programari.update', $prog->id) }}" method="POST" style="display:inline">
        @csrf
        @method('PUT')
        <input type="hidden" name="status" value="amanata"><button type="submit" class="btn-icon delay" title="Amână"><i class="fas fa-clock"></i></button>
    </form>

    <a href="{{ route('programari.edit', $prog->id) }}" class="btn-icon edit" title="Editează"><i class="fas fa-pen"></i></a>

    <form action="{{ route('programari.destroy', $prog->id) }}" method="POST" style="display:inline">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn-icon del" title="Șterge"onclick="return confirm('Sigur vrei să ștergi această programare?')"><i class="fas fa-times"></i></button>
    </form>
</td>
                
            </tr>
        @empty
        <tr>
            <td colspan="4" style="text-align:center;padding-top:20px;">
                Nu există programări viitoare.
            </td>
        </tr>
        @endforelse
    </table>
    <div style="margin-top:10px; display:flex; flex-direction: column; align-items: center; padding:10px;">
        {{ $programariViitoare->appends(request()->query())->links('pagination::bootstrap-5') }}
    </div>    
</div>

    <div class="card">
        <h2>Programări trecute</h2>

    <table cellspacing="10" cellpadding="5">
        <tr>
            <th>Data</th>
            <th>Pacient</th>
            <th>Detalii</th>
        </tr>
        @forelse($programariFinalizate as $prog)
            <tr>
                <td>{{ $prog->data->format('d.m.Y, H:i') }}</td>
                <td>{{ $prog->pacient->nume }} {{ $prog->pacient->prenume }}</td>
                <td><em>{{ $prog->detalii }}</em></td>
            </tr>
        @empty
        <tr>
            <td colspan="4" style="text-align:center;padding-top:20px;">
                Nu există programări viitoare.
            </td>
        </tr>            
        @endforelse          
    </table>
    <div style="margin-top:10px; display:flex; flex-direction: column; align-items: center; padding:10px;">
        {{ $programariFinalizate->appends(request()->query())->links('pagination::bootstrap-5') }}
    </div>    
    </div>

    <div class="card">
        <h2>Programări amanate</h2>

    <table cellspacing="10" cellpadding="5">
        <tr>
            <th>Data</th>
            <th>Pacient</th>
            <th>Detalii</th>
        </tr>        
            @forelse($programariAmanate as $prog)
           <tr>
                <td>{{ $prog->data->format('d.m.Y, H:i') }}</td>
                <td>{{ $prog->pacient->nume }} {{ $prog->pacient->prenume }}</td>
                <td><em>{{ $prog->detalii }}</em></td>
            </tr>
        @empty
            <tr>
                <td colspan="4" style="text-align:center;padding-top:20px;">
                    Nu există programări amanate.
                </td>
            </tr>
        @endforelse
    </table>
    <div style="margin-top:10px; display:flex; flex-direction: column; align-items: center; padding:10px;">
        {{ $programariAmanate->appends(request()->query())->links('pagination::bootstrap-5') }}
    </div>      
    </div>
</div>

{{-- FullCalendar --}}
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.js'></script>
{{-- <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script> --}}
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/locales-all.global.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('calendar');

    var calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        displayEventTime: false, 
         locale: 'ro',
         buttonText: {
            today: 'Astăzi'
         },
        events: [
            @foreach($programari as $prog)
                {
                    title: "{{ $prog->data->format('H:i') }}, {{ $prog->pacient->nume }} {{ $prog->pacient->prenume }}",
                    start: "{{ $prog->data->format('Y-m-d\TH:i:s') }}",
                    // description: "{{ $prog->detalii }}"
                    color: "{{ $prog->status == 'finalizata' ? '#8eab8e' : ($prog->status == 'amanata' ? '#f0ac4edf' : '#4aa8e7') }}"
                   
                },
            @endforeach
        ],
        // eventDidMount: function(info) {
        //     if (info.event.extendedProps.description) {
        //         var tooltip = new Tooltip(info.el, {
        //             title: info.event.extendedProps.description,
        //             placement: 'top',
        //             trigger: 'hover',
        //             container: 'body'
        //         });
        //     }
        // }
    });

    calendar.render();
});
</script>
@endsection
