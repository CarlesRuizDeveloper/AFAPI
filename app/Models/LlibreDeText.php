<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LlibreDeText extends Model
{
    use HasFactory;

    protected $table = 'llibres_de_text';

    protected $fillable = ['titol', 'curs', 'editorial', 'observacions'];
}
