<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use HasFactory;
    protected $fillable = [
       "question",
       "restaurant_id",
       "is_default"
    ];

    public function tag() {
        return $this->hasMany(Tag::class,'question_id','id');
    }
}
