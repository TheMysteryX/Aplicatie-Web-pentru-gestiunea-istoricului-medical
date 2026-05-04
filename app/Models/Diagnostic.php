<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Diagnostic extends Model
{
    protected $table = 'diagnostice';
    protected $fillable = ['id_pacient','id_medic', 'id_programare', 'nume','descriere','data'];

    public function pacient() { return $this->belongsTo(Pacient::class, 'id_pacient'); }
    public function medic() { return $this->belongsTo(User::class, 'id_medic'); }
    public function programare() { return $this->belongsTo(Programare::class, 'id_programare');}
    public function retete() { return $this->hasMany(Reteta::class, 'id_diagnostic'); }
    public function tratamente() { return $this->hasMany(Diagnostic::class, 'id_diagnostic'); }
}
