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
        <h3 align='center'>Meniu principal</h3> <p></p><p></p>
        <div class="buttons" style="padding-bottom:30px;" align='center'>
            <a href="{{ route('programari.index') }}" class="form-button">Programări</a>
            <a href="{{ route('pacienti.index') }}" class="form-button">Pacienți</a>
            <a href="{{ route('retete.index') }}" class="form-button">Rețete</a>
            <a href="{{ route('diagnostice.index') }}" class="form-button">Diagnostice</a>
            <a href="{{ route('tratamente.index') }}" class="form-button">Tratamente</a>
            <a href="{{ route('trimiteri.index') }}" class="form-button">Trimiteri</a>
        </div>
    </div>

    <div class="card">
    <h2>Dashboard</h2>
    
    <h3>Solicitări de programare primite</h3><p></p>
    @if ($solicitari->count() > 0)
    <table cellspacing="10" cellpadding="5">
    <tr>
        <th>Pacient</th>
        <th>Perioada</th>
        <th>Mesaj</th>
        <th>Acțiuni</th>
    </tr>

    @foreach($solicitari as $solicitare)
    <tr>
        <td>{{ $solicitare->pacient->nume }}</td>
        <td>
            {{ $solicitare->data_start }}
            -
            {{ $solicitare->data_end }}
        </td>
        <td>{{ $solicitare->mesaj }}</td>
        <td>

            <a href="{{ route('programari.create', [
                'pacient_id' => $solicitare->pacient_id,
                'solicitare_id' => $solicitare->id
            ]) }}">
                Creează programare
            </a>

            <form method="POST"
                action="{{ route('medic.solicitari.respinge', $solicitare) }}">
                @csrf
                <button type="submit">Respinge</button>
            </form>

        </td>
    </tr>
    @endforeach
    </table>
    @else
        <p>Nu există solicitări de programare.</p>
    @endif
</div>
    <div class="card">
        <h3>Următoarea programare</h3><p></p>
        @if ($nextProgram)
            <table cellspacing="10" cellpadding="5">
                <tr>
                    <th>Pacient</th>
                    <th>Data</th>
                    <th>Detalii</th>
                </tr>
                <tr>
                    <td>{{ $nextProgram->pacient->nume }} {{ $nextProgram->pacient->prenume }}</td>
                    <td>{{ $nextProgram->data }}</td>
                    <td>{{ $nextProgram->detalii }}</td>
                </tr>
            </table>
        @else
            <p>Nu există programări viitoare.</p>
        @endif

        <h3>Ultimele programări</h3>
        @if ($lastProgram->count() > 0)
            <table cellspacing="10" cellpadding="5">
                <tr>
                    <th>Pacient</th>
                    <th>Data</th>
                    <th>Detalii</th>
                </tr>
                @foreach ($lastProgram as $programare)
                    <tr>
                        <td>{{ $programare->pacient->nume }} {{ $programare->pacient->prenume }}</td>
                        <td>{{ $programare->data }}</td>
                        <td>{{ $programare->detalii }}</td>
                    </tr>
                @endforeach
            </table>
        @else
            <p>Nu există programări recente.</p>
        @endif
    </div>

    <div class="card">
        <h3>Programări cu întârziere</h3>
        @if ($delayedPrograms->count())
            <table cellspacing="10" cellpadding="5">
                <tr>
                    <th>Data</th>
                    <th>Pacient</th>
                </tr>
                @foreach ($delayedPrograms as $p)
                    <tr>
                        <td>{{ $p->data }}</td>
                        <td>{{ $p->pacient->nume }} {{ $p->pacient->prenume }} </td>
                    </tr>
                @endforeach
            </table>
        @else
            <p>Nu există programări întârziate.</p>
        @endif
    </div>

<div class="card">
    <h3>Statistici săptămâna curentă</h3>
    <h4>Programari:</h4>
    <p>Total: {{ $stats['total'] }}</p>
    <p>Finalizate: {{ $stats['finalizate'] }}</p>
    <p>Amanate: {{ $stats['amanate'] }}</p>
</div>

    <div class="card">
        <h3>Rețete care expiră în curând</h3><p></p>
        @if ($expiringPrescriptions->count())
            <table cellspacing="10" cellpadding="5">
                <tr>
                    <th>Diagnostic</th>
                    <th>Pacient</th>
                    <th>Data expirare</th>
                </tr>
                @foreach ($expiringPrescriptions as $r)
                    <tr>
                        <td>{{ $r->diagnostic->nume }}</td>
                        <td>{{ $r->pacient->nume }} {{ $r->pacient->prenume }}</td>
                        <td>{{ $r->data_expirare }}</td>
                    </tr>
                @endforeach
            </table>
        @else
            <p>Nicio rețetă care expiră în următoarele 7 zile.</p>
        @endif
    </div>

    <div class="card">
        <h3>Tratamente care expiră în curând</h3><p></p>
        @if ($expiringTratamente->count())
            <table cellspacing="10" cellpadding="5">
                <tr>
                    <th>Diagnostic</th>
                    <th>Titlu</th>
                    <th>Pacient</th>
                    <th>Data sfârșit</th>
                </tr>
                @foreach ($expiringTratamente as $t)
                    <tr>
                        <td>{{ $t->diagnostic->nume }}</td>
                        <td>{{ $t->nume }}</td>
                        <td>{{ $t->pacient->nume }} {{ $t->pacient->prenume }}</td>
                        <td>{{ $t->data_sfarsit }}</td>
                    </tr>
                @endforeach
            </table>
        @else
            <p>Niciun tratament care expiră în următoarele 7 zile.</p>
        @endif
    </div>

</div>
@endsection

{{-- <div class="card">
    <h3>👥 Pacienți cu programări frecvente</h3>
    @if ($frequentPatientsData->count())
        <ul>
            @foreach ($frequentPatientsData as $pacient)
                <li>{{ $pacient->nume }} {{ $pacient->prenume }}</li>
            @endforeach
        </ul>
    @else
        <p>Niciun pacient frecvent.</p>
    @endif
</div> --}}

