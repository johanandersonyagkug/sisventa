<?php

// app/Models/Venta.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Venta extends Model
{
    use HasFactory;

   
    protected $fillable = [
        'cliente_id', 
        'numero', 
        'total', 
        'fecha'
    ];

    // Relationship with Cliente (Customer)
    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }

    // Relationship with Producto (Product) - Many-to-Many
    public function productos()
    {
        return $this->belongsToMany(Producto::class, 'producto_venta')
                    ->withPivot('cantidad')
                    ->withTimestamps();
    }
}
