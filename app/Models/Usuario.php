<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Usuario extends Model
{
    use HasFactory;

    protected $primaryKey = "codTipoUsiario";
    protected $table = "usuarios";
    protected $fillable = ['dni','contrasenia','activo','trabajador_id','tusuario_id'];

    //Relacion Uno a Uno con Trabajador
    public function trabajador(){
        return $this->belongsTo('App\Models\Trabajador');
    }

    //Relacion Una a Muchos inversa
    public function tusuario(){
        return $this->belongsTo('App\Models\Tusuario');
    }

    public $timestamps = false;

}
