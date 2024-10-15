<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LlibreDeText extends Model
{
    use HasFactory;

    protected $table = 'llibre_de_texts';

    protected $fillable = ['titol', 'curs', 'editorial', 'observacions', 'category_id', 'user_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
