<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContactoEmergencia extends Model
{
    use HasFactory;

    protected $primaryKey = "codConEmergencia";
    protected $table = "contactoemergencia";

    protected $fillable = ['nombre','numero','parentesco'];

    public $timestamps = false;
}
