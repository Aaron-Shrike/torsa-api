<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Departamento extends Model
{
    use HasFactory;

    protected $primaryKey = "codDepartemnto"; 
    protected $table = "departamento"; 
    protected $fillable = ['nombre'];    

    public $timestamps = false;
}
