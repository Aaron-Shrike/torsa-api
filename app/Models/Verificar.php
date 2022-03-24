<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Verificar extends Model
{
    use HasFactory;

    protected $primaryKey = "codVerificar"; 
    protected $table = "verificar"; 
    protected $fillable = ['codSolicitud','v1','v2','v3','v4'];    

    public $timestamps = false;
}
