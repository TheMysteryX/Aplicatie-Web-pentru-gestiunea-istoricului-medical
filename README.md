# Clinica Life - Sistem Management Medical

Aplicație web dezvoltată în **Laravel** pentru gestionarea unei clinici medicale. Sistemul permite administrarea pacienților, medicilor, programărilor, rețetelor, diagnosticelor, tratamentelor și trimiterilor.

---

## Funcționalități principale

### Admin
- Gestionare:
  - Medici
  - Specializări
  - Sistem

### Medic
- Adăugare și vizualizare pacienți
- Gestionare:
  - Programări
  - Rețete
  - Diagnostice
  - Tratamente
  - Trimiteri
- Filtrare, sortare și paginare pentru fiecare entitate

### Pacient
- Vizualizare dashboard personal
- Istoric medical complet:
  - Programări
  - Rețete
  - Diagnostice
  - Tratamente
  - Trimiteri
- Notificări pentru solicitări de programare
- Filtrare și sortare date

### Programări
- Creare solicitări programare
- Acceptare / respingere de către medic
- Notificare pacient

### Rețete
- Asociere cu diagnostic
- Filtrare după:
  - diagnostic
  - dată emitere / expirare

### Diagnostice
- Asociate cu pacient și medic
- Căutare și sortare după nume și dată

### Tratamente
- Asociate cu diagnostic
- Perioadă de desfășurare
- Instrucțiuni tratament

### Trimiteri
- Căutare după titlu / locație
- Gestionare perioadă valabilitate

---

## Tehnologii utilizate
- Backend: Laravel 12
- PHP 8.4
- MySQL
- Frontend:
  - Blade Templates
  - Bootstrap 5
  - FontAwesome
- Autentificare Laravel (user + roluri)

---


