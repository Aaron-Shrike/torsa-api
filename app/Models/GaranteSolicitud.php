<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GaranteSolicitud extends Model
{
    use HasFactory;

    protected $table = "garantesolicitud";
    protected $primaryKey = "codGaranteSolicitud";

    protected $fillable = ['codSolicitud','codSocio'];

    public $timestamps = false;
}
