<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SolicitareProgramare extends Model
{
    protected $table = 'solicitari_programari';

    protected $fillable = [
        'pacient_id',
        'medic_id',
        'programare_id',
        'data_start',
        'data_end',
        'mesaj',
        'status'
    ];

    public function pacient()
    {
        return $this->belongsTo(Pacient::class);
    }

    public function medic()
    {
        return $this->belongsTo(User::class, 'medic_id');
    }

    public function programare()
    {
        return $this->belongsTo(Programare::class, 'programare_id');
    }
}