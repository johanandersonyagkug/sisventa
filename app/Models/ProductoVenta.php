<?php



namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductoVenta extends Model
{
    use HasFactory;

    protected $table = 'producto_venta';

    protected $fillable = [
        'venta_id', 
        'producto_id', 
        'cantidad'
    ];

    // Relationship with Venta (Sale)
    public function venta()
    {
        return $this->belongsTo(Venta::class);
    }

    // Relationship with Producto (Product)
    public function producto()
    {
        return $this->belongsTo(Producto::class);
    }
   
}
