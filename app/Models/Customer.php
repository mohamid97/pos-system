<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
class Customer extends Model
{
      use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'address',
        'total_purchases'
    ];

    protected $casts = [
        'total_purchases' => 'decimal:2',
    ];

    public function sales(): HasMany
    {
        return $this->hasMany(Sale::class);
    }
}