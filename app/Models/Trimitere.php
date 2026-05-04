<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Trimitere extends Model
{
    protected $table = 'trimiteri';
    protected $fillable = ['id_pacient','id_medic','id_programare','titlu','detalii','locatie','data_emitere','data_expirare'];

    public function pacient() { return $this->belongsTo(Pacient::class, 'id_pacient'); }
    public function medic() { return $this->belongsTo(User::class, 'id_medic'); }
    public function programare() { return $this->belongsTo(Programare::class, 'id_programare');}

}
