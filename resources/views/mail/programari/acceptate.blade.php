@component('mail::message')
# Programare acceptată!

Bună ziua, **{{ $programare->pacient->prenume }}**! 

Programarea dumneavoastră a fost **acceptată**.

---

### Detalii programare

- **Data:** {{ $programare->data }}
- **Medic:** {{ $programare->medic->nume }} {{ $programare->medic->prenume }}

---

Vă așteptăm!

Mulțumim,<br>
**Spitalul Life**
@endcomponent