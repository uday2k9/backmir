<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    protected $fillable=[
        
        'user_id',
        'product_id',
        'product_name',
        'quantity',
        'amount',
        'duration',
        'form_factor',
        'sub_total',
        'is_wholesale',        
        'created_at',
        'updated_at'
       ];
}
