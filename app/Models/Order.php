<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    /**
     * Mass-assignable fields
     */
    protected $fillable = [
        'user_id',
        'product_id',
        'amount',
        'status',
        'mpesa_receipt',
    ];

    /**
     * Relationship: Order belongs to a User
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relationship: Order belongs to a Product
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
