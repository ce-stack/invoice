<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;
    protected $fillable = ['customer_id', 'amount', 'description', 'status', 'invoice_date'];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}

