<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tusuario extends Model
{
    use HasFactory;

    protected $primaryKey = "codTipoUsuario";
    protected $table = "tusuarios";
    protected $fillable = ['descripcion'];

     //Relacion Uno a Muchos
     public function usuarios(){
        return $this->hasMany('App\Models\usuarios');
    }

    public $timestamps = false;
}
