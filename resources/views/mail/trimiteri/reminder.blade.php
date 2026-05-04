@component('mail::message')
# Reminder Trimitere!
Bună ziua, **{{ $trimitere->pacient->prenume }}**!
Acesta este un reminder pentru expirarea trimiterei dumneavoastră pe data de **{{ $trimitere->data_expirare }}** emisa de medicul **{{ $trimitere->medic->nume }} {{ $trimitere->medic->prenume }}**.

Vă așteptăm!

Mulțumim,<br>
**Spitalul Life**
@endcomponent