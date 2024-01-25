<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Servicio extends Model
{
    use HasFactory;


    protected $table = 'servicios';

    protected $fillable = ['servicio', 'descripcion'];

    protected $casts = [
        'servicio' => 'string',
        'descripcion' => 'string'
    ];

    protected $attributes = [
        'servicio' => '',
        'descripcion' => ''
    ];

    public function clientes()
    {
        return $this->belongsToMany(Cliente::class, 'cliente_servicio', 'id_servicio', 'nro_cliente');
    }


    public function codigos()
    {
        return $this->belongsToMany(Codigo::class, 'servicio_codigos', 'id_servicio', 'codigo');
    }


    public function servicioCodigos()
{
    return $this->hasMany(ServicioCodigo::class, 'id_servicio', 'id');
}
}



