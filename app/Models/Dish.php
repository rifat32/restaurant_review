<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dish extends Model
{
    use HasFactory;



    protected $fillable = [
        "name",
        "price",
        "restaurant_id",
        "menu_id",
        "image",
        "description",
        "take_away",
        "delivery",
        "type",
        "ingredients",
        "calories"
    ];

    public function deal() {
        return $this->hasOne(Deal::class,"dish_id","id");
    }
}
