<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Facturacion extends Model
{
    use HasFactory;

    protected $table = 'facturaciones';
    protected $fillable = ['nro_clientecliente','id_servicio','periodo','cantidad_activos','cantidad_inactivos','remito','modulo'];

    protected $casts = [
        'cantidad' => 'integer'
    ];

    // public function cliente()
    // {
    //     return $this->belongsTo(Cliente::class, 'id_cliente', 'nro_cliente');
    // }

    public function servicio()
    {
        return $this->belongsTo(Servicio::class, 'id_servicio', 'id');
    }
}
