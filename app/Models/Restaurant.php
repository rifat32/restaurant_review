<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Restaurant extends Model
{
    use HasFactory;
    protected $fillable = [
        "Name",
        "Address",
        "PostCode",
        "OwnerID",
        "Status",
        "Key_ID",
        "expiry_date",
        "enable_question"
    ];

    public function owner() {
        return $this->hasOne(User::class,'id','OwnerID');
    }
    public function table() {
        return $this->hasMany(RestaurantTable::class,'id','restaurant_id');
    }

}
