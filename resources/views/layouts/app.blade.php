<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Clinica Life</title>
        <link href="{{ asset('css/styles.css') }}" rel="stylesheet">
    </head>
    <body>
        <div class="header">
            <div class="logo">Spitalul Life</div>
                <ul>
                @auth
                    @if(auth()->user()->rol == 'medic')
                        <li><a href="{{ route('programari.index') }}">Programări |</a></li>
                        <li><a href="{{ route('pacienti.index') }}">Pacienți |</a></li>
                        <li><a href="{{ route('retete.index') }}">Rețete |</a></li>
                        <li><a href="{{ route('diagnostice.index') }}">Diagnostice |</a></li>
                        <li><a href="{{ route('tratamente.index') }}">Tratamente |</a></li>
                        <li><a href="{{ route('trimiteri.index') }}">Trimiteri |</a></li>
                        <li><a href="{{ route('medic.dashboard') }}">Dashboard |</a></li>
                        <li><a href="{{ route('cont') }}">Cont</a></li>
                    @elseif (auth()->user()->rol == 'admin')
                        <li><a href="{{ route('admin.dashboard') }}">Dashboard |</a></li>
                        <li><a href="{{ route('cont') }}">Cont</a></li>
                    @elseif(auth()->user()->rol == 'pacient')
                        <li><a href="{{ route('pacient.dashboard') }}">Dashboard |</a></li>
                        <li><a href="{{ route('cont') }}">Cont</a></li>
                    @endif
                    @endauth
                </ul>
        </div>
        @yield('content')

        <div class="footer">
            &copy; 2025 - Spitalul Life. Toate drepturile rezervate.
        </div>
    </body>
</html>