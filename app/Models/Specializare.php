<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Specializare extends Model
{
    protected $table = 'specializari';
    protected $fillable = ['nume'];
    public function medici() {
        return $this->hasMany(User::class, 'spec_id');
    }
}
