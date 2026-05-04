<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'nume',
        'prenume',
        'email',
        'password',
        'rol',
        'spec_id',
        'cnp',
        'data_nasterii',
        'adresa',
        'telefon',
    ];

    public function isAdmin() { 
        return $this->rol === 'admin'; 
    }
    public function isMedic() { 
        return $this->rol === 'medic'; 
    }

    public function specializare() {
        return $this->belongsTo(Specializare::class,'spec_id');
    }
    public function pacienti() { 
        return $this->belongsToMany(Pacient::class, 'medic_pacient', 'id_medic','id_pacient'); 
    }
    public function programari() { 
        return $this->hasMany(Programare::class, 'id_medic'); 
    }
    public function retete() { 
        return $this->hasMany(Reteta::class, 'id_medic'); 
    }
    public function diagnostice() { 
        return $this->hasMany(Diagnostic::class, 'id_medic'); 
    }
    public function tratamente() { 
        return $this->hasMany(Tratament::class, 'id_medic'); 
    }
    public function trimiteri() { 
        return $this->hasMany(Trimitere::class, 'id_medic'); 
    }

    public function solicitariPrimite()
    {
        return $this->hasMany(SolicitareProgramare::class, 'medic_id');
    }

    public function pacient()
    {
        return $this->hasOne(Pacient::class);
    }

    public function isPacient()
    {
        return $this->rol === 'pacient';
    }    

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];
    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}
