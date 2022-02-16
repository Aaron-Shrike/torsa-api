<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Solicitud extends Model
{
    use HasFactory;

    protected $primaryKey = "idSolicitud";
    protected $table = "solicituds";

    public $timestamps = false;
}
