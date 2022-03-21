<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Distrito extends Model
{
    use HasFactory;

    protected $primaryKey = "codDistrito"; 
    protected $table = "distrito"; 
    protected $fillable = ['codProvincia','nombre'];    

    public $timestamps = false;
}
