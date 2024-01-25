<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServicioCodigo extends Model
{
    use HasFactory;

    protected $table = 'servicio_codigos';
    protected $fillable = ['id_servicio', 'codigo'];
    protected $primaryKey = 'id';
    public $incrementing = true;
    public $timestamps = true;

    public function servicio()
    {
        return $this->belongsTo(Servicio::class, 'id_servicio', 'id');
    }

    public function codigo()
    {
        return $this->belongsTo(Codigo::class, 'codigo', 'codigo');
    }
}