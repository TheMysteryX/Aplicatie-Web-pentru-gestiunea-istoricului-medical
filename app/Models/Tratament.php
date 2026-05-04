<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tratament extends Model
{
    protected $table = 'tratamente';
    protected $fillable = ['id_pacient','id_medic','id_diagnostic','nume','instructiuni','data_inceput','data_sfarsit'];

    public function pacient() { return $this->belongsTo(Pacient::class, 'id_pacient'); }
    public function medic() { return $this->belongsTo(User::class, 'id_medic'); }
    public function diagnostic() { return $this->belongsTo(Diagnostic::class, 'id_diagnostic'); }

}
