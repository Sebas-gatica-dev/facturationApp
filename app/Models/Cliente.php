<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    use HasFactory;

    protected $primaryKey = 'id';
    public $incrementing = true;
    protected $keyType = 'integer';
    protected $fillable = ['nro_cliente', 'nombre', 'database', 'estado','modulo', 'separated','separated_reference','nombre_fantasia'];

    public function servicios()
    {
        return $this->belongsToMany(Servicio::class, 'cliente_codigos', 'id', 'id_servicio');
    }
}


