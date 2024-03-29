<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Trabajador extends Model
{
    use HasFactory;

    protected $primaryKey = "codTrabajador";
    protected $table = "trabajador";

    protected $fillable = [
        'codConEmergencia','codTipoCargo','codDistrito','nombre','apePaterno','apeMaterno','fecNacimiento','telefono','domicilio','correo'
    ];

    public $timestamps = false;
}
