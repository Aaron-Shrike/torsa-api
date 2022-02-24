<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipoCargo extends Model
{
    use HasFactory;

    protected $primaryKey = "codTipoCargo"; 
    protected $table = "tipocargo"; 
    protected $fillable = ['descripcion'];    

    public $timestamps = false;
}
