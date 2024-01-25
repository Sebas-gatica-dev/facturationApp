<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Historial extends Model
{
    use HasFactory;

    protected $table = 'historials';
    protected $primaryKey = 'id';
    public $incrementing = true;
    protected $fillable = ['periodo'];
    // En el modelo Historial.php
    protected $attributes = [
        'periodo_name' => '',
     ];



}
