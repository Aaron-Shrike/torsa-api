<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Socio extends Model
{
    use HasFactory;

   protected $table = "socio";
   protected $primaryKey = "codSocio";
   
   protected $fillable = ['dni','nombre','apePaterno','apeMaterno','fecNacimiento','telefono','domicilio','tipo','activo'];

   public $timestamps = false;
}
