<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cemergencia extends Model
{
    use HasFactory;

    protected $primaryKey = "codConEmergencia";
    protected $table = "cemergencias";

    protected $fillable = ['nombre','numero','parentesco'];

    //Relacion Uno a Muchos
    public function trabajadors(){
        return $this->hasMany('App\Models\Trabajador');
    }

    public $timestamps = false;
}
