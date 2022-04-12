<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VariationType extends Model
{
    protected $table = "variation_types";
    use HasFactory;
    protected $fillable = [
        "name",
        "description",
        "restaurant_id",
    ];
    public function variation() {
        return $this->hasMany(Variation::class,"type_id","id");
    }
    public function dish_variation() {
        return $this->hasMany(DishVariation::class,"type_id","id");
    }

}
