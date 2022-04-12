<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        "type",
        "qty",
        "order_id",
        "dish_id",
     ];
     public function dish() {
        return $this->hasMany(Dish::class,"id","dish_id");
    }
}
