<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReviewNew extends Model
{
    use HasFactory;
    protected $fillable = [
        'description',
        'restaurant_id',
        'rate',
        'user_id',
        'comment',
        "question_id",
        "tag_id"
    ];
    public function question() {
        return $this->hasOne(Question::class,'id','question_id');
    }
    public function tag() {
        return $this->hasOne(Question::class,'id','tag_id');
    }
    public function restaurant() {
        return $this->hasOne(Question::class,'id','restaurant_id');
    }

}
