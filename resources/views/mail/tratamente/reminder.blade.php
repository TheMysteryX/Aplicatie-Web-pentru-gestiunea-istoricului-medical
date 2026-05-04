@component('mail::message')
# Reminder Tratament!
Bună ziua, **{{ $tratament->pacient->prenume }}**!
Acesta este un reminder pentru expirarea tratamentului dumneavoastră de la data de **{{ $tratament->data_sfarsit }}** emisa de medicul **{{ $tratament->medic->nume }} {{ $tratament->medic->prenume }}** pentru diagnosticul **{{ $tratament->diagnostic }}**.

Vă așteptăm!

Mulțumim,<br>
**Spitalul Life**
@endcomponent