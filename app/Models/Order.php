<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    protected $fillable = [
        "amount",
        "table_number",
        "restaurant_id",
        "status",
        "discount",
        "payment_method",
        "remarks",
        "type",
        "autoprint",
        "order_by",
        "customer_id",
        "customer_name",
        "cash",
        "card"
    ];
    public function detail() {
        return $this->hasMany(OrderDetail::class,"order_id","id");
    }
    public function user() {
        return $this->hasOne(User::class,"id","customer_id");
    }
    public function restaurant() {
        return $this->hasOne(Restaurant::class,"id","restaurant_id");
    }
    public function ordervariation() {
        return $this->hasMany(OrderVariation::class,"order_id","id");
    }
}
