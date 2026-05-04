@component('mail::message')
# Reminder Reteta!
Bună ziua, **{{ $reteta->pacient->prenume }}**!
Acesta este un reminder pentru expirarea retetei dumneavoastră de la data de **{{ $reteta->data_expirare }}** emisa de medicul **{{ $reteta->medic->nume }} {{ $reteta->medic->prenume }}** pentru diagnosticul **{{ $reteta->diagnostic }}**.

Vă așteptăm!

Mulțumim,<br>
**Spitalul Life**
@endcomponent