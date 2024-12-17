<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Producto extends Model
{
    use HasFactory;

    // Campos que se pueden asignar de forma masiva
    protected $fillable = [
        'nombre',
        'descripcion',
        'precio',
        'stock',
        'categoria_id',
    ];

    // Relación con la tabla 'categorias'
    public function categoria()
    {
        return $this->belongsTo(Categoria::class);
    }
    public function ventas()
    {
        return $this->belongsToMany(Venta::class, 'producto_venta')
                    ->withPivot('cantidad');
    }
}
