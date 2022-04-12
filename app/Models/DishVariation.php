<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DishVariation extends Model
{
    use HasFactory;
    protected $fillable = [
        "no_of_varation_allowed",
        "type_id",
        "dish_id"
    ];

    public function variation_type() {
        return $this->belongsTo(VariationType::class,"type_id","id");
    }
}
