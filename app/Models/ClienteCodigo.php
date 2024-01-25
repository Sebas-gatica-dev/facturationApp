<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClienteCodigo extends Model
{
    use HasFactory;

    protected $table = 'cliente_codigos';
    protected $primaryKey = ['nro_cliente', 'id_servicio'];
    public $incrementing = false;
    protected $keyType = 'integer';
    protected $fillable = ['nro_cliente', 'id_servicio', 'codigo'];

    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'nro_cliente', 'nro_cliente');
    }

    public function servicio()
    {
        return $this->belongsTo(Servicio::class, 'id_servicio', 'id');
    }

    public function codigo()
    {
        return $this->belongsTo(Codigo::class, 'codigo', 'codigo');
    }
}