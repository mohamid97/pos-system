<?php

namespace App\Events;


use App\Models\Product;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\InteractsWithSockets;

class LowStockAlert
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    
    public Product $product;
    
    public function __construct(Product $product)
    {
         $this->product = $product;
    }
    
}