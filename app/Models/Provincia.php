<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Provincia extends Model
{
    use HasFactory;

    protected $primaryKey = "codProvincia"; 
    protected $table = "provincia"; 
    protected $fillable = ['codDepartamento','nombre'];    

    public $timestamps = false;
}
