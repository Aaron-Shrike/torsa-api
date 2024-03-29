<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Departamento extends Model
{
    use HasFactory;

    protected $primaryKey = "codDepartamento"; 
    protected $table = "departamento"; 
    protected $fillable = ['codDepartamento','nombre'];    

    public $timestamps = false;
}
