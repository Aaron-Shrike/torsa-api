<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Trabajador extends Model
{
    use HasFactory;

    protected $primaryKey = "codTrabajador";
    protected $table = "trabajadors";

    protected $fillable = [
        'nombre','apePaterno','apeMaterno','fecNacimiento','telefono','domicilio','correo','tcargo','cemergencia'
    ];

    //Relacion uno a uno con Usuario
   public function usuario(){
    return $this->hasOne('App\Models\Usuario');
    }

    //Relacion Uno a Muchos inversa
    public function cemergencia(){
        return $this->belongsTo('App\Models\Cemergencia');
    }

    //Relacion Uno a Muchos inversa
    public function tcargo(){
        return $this->belongsTo('App\Models\Tcargo');
    }

    public $timestamps = false;
}
