<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExternalClient extends Model
{
    use HasFactory;


    /**
     * REFERENCIA A LA CONEXION EXTERNA.
     *
     * @var string
     */
    protected $connection = 'external';


    /**
     * TABLA DE LA BASE DE DATOS CLIENTUP.
     *
     * @var string
     */
    protected $table = 'clientes';
}
