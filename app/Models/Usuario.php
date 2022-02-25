<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Usuario extends Model
{
    use HasFactory;

    protected $primaryKey = "codUsuario";
    protected $table = "usuario";
    protected $fillable = ['dni','activo','contrasenia','codTrabajador','codTipoUsuario'];

    protected $hidden = [];

    public $timestamps = false;

}
