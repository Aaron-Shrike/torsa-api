<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Solicitud extends Model
{
    use HasFactory;

    protected $primaryKey = "codSolicitud";
    protected $table = "solicitud";

    protected $fillable = ['monto','motivo','fecha','estado','codUsuario','codSocio'];

    public $timestamps = false;
}
