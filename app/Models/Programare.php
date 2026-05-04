<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Programare extends Model
{
    protected $table = 'programari';
    protected $fillable = ['id_pacient','id_medic','data','status','detalii'];

    protected $casts = [
        'data' => 'datetime',
    ];
    
    public function pacient() { return $this->belongsTo(Pacient::class, 'id_pacient'); }
    public function medic() { return $this->belongsTo(User::class, 'id_medic'); }
    public function diagnostice(){ return $this->hasMany(Diagnostic::class, 'id_programare'); }
    public function trimiteri(){ return $this->hasMany(Trimitere::class, 'id_programare'); }
}
