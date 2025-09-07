<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    /**
     * assignable fields
     */
    protected $fillable = [
        'user_id',
        'product_id',
        'amount',
        'status',
        'mpesa_receipt',
    ];// assignable fields

    /**
     * Relationship: Order belongs to a User
     */
    public function user()
    {
        return $this->belongsTo(User::class);// Relationship: Order belongs to a User
    }

    
     // Relationship: Order belongs to a Product
     
    public function product()// Relationship: Order belongs to a Product
    {
        return $this->belongsTo(Product::class);//
    }
}
