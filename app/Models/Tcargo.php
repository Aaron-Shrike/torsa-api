<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tcargo extends Model
{
    use HasFactory;

    protected $primaryKey = "codTipoCargo";
    protected $table = "tcargos";
    protected $fillable = ['descripcion'];

    //Relacion Uno a Muchos
    public function trabajadors(){
        return $this->hasMany('App\Models\Trabajador');
    }

    public $timestamps = false;
}
