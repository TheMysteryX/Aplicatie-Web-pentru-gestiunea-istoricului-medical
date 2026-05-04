<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reteta extends Model
{
    protected $table = 'retete';
    protected $fillable = ['id_pacient','id_medic', 'id_diagnostic', 'data_emitere','data_expirare','medicamente'];

    public function pacient() { return $this->belongsTo(Pacient::class, 'id_pacient'); }
    public function medic() { return $this->belongsTo(User::class, 'id_medic'); }
    public function diagnostic() { return $this->belongsTo(Diagnostic::class, 'id_diagnostic'); }

}
