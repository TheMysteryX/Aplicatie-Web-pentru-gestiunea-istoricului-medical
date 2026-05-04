@component('mail::message')
# Reminder Programare!
Bună ziua, **{{ $programare->pacient->prenume }}**!
Acesta este un reminder pentru programarea dumneavoastră de la data de **{{ $programare->data }}** cu medicul **{{ $programare->medic->nume }} {{ $programare->medic->prenume }}**.

Vă așteptăm!

Mulțumim,<br>
**Spitalul Life**
@endcomponent