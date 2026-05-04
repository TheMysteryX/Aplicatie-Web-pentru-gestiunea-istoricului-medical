<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pacient extends Model
{
    protected $table = 'pacienti';
    protected $fillable = ['nume','prenume','data_nasterii', 'cnp', 'telefon', 'adresa', 'asigurat/a', 'user_id'];

    public function medici(){ return $this->belongsToMany(User::class, 'medic_pacient', 'id_pacient', 'id_medic'); }
    public function programari() { return $this->hasMany(Programare::class, 'id_pacient'); }
    public function retete() { return $this->hasMany(Reteta::class, 'id_pacient'); }
    public function diagnostice() { return $this->hasMany(Diagnostic::class, 'id_pacient'); }
    public function tratamente() { return $this->hasMany(Tratament::class, 'id_pacient'); }
    public function trimiteri() { return $this->hasMany(Trimitere::class, 'id_pacient'); }

    public function user() { return $this->belongsTo(User::class); }
    public function solicitari() { return $this->hasMany(SolicitareProgramare::class); }
}
