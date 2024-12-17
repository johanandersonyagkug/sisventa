<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    use HasFactory;

    // Definir los campos que pueden ser llenados de manera masiva
    protected $fillable = [
        'dni',
        'nombre',
        'apellido_paterno',
        'apellido_materno',
        'telefono',
        'direccion',
    ];

    // Si necesitas personalizar la tabla (por ejemplo, si la tabla no sigue la convención de pluralización de Laravel)
    protected $table = 'clientes';  // Este paso es opcional si la tabla sigue la convención

    public function ventas()
    {
        return $this->hasMany(Venta::class);
    }
}
