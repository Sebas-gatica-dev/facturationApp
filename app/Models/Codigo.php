<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Codigo extends Model
{
    use HasFactory;

    protected $table = 'codigos';
    protected $primaryKey = 'codigo';
    public $incrementing = false;
    protected $keyType = 'integer';
    protected $fillable = ['codigo', 'description'];


    public function servicios()
    {
        return $this->belongsToMany(Servicio::class, 'servicio_codigos', 'codigo', 'id_servicio');
    }
}
