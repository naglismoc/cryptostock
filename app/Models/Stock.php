<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    use HasFactory;


    public function stockUsers()
    {
        return $this->hasMany(UserStock::class, 'stock_id', 'id');
    }
}
