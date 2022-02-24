<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipoUsuario extends Model
{
    use HasFactory;

    protected $primaryKey = "codTipoUsuario";
    protected $table = "tipousuario";
    protected $fillable = ['descripcion'];

    public $timestamps = false;
}
