<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use App\Models\Venta;
use App\Models\Cliente;
use App\Models\Categoria;

class DashboardController extends Controller
{
    public function index()
{
    // Obtener el total de las categorías
    $categorias = Categoria::count();

    // Sumar todos los totales de las ventas
    $ventas = Venta::sum('total');

    // Obtener el total de productos
    $productos = Producto::count();

    // Obtener todos los clientes
    $clientes = Cliente::count();

    // Obtener los productos con stock bajo
    $productos_bajos_stock = Producto::where('stock', '<', 5)->get();

    // Obtener los 4 productos más vendidos
    $productos_mas_vendidos = Producto::withCount(['ventas as total_ventas' => function ($query) {
        $query->selectRaw('sum(cantidad)'); // Sumar la cantidad de productos vendidos
    }])
    ->orderBy('total_ventas', 'desc') // Ordenar por la suma total de ventas
    ->take(4) // Limitar a los 4 productos más vendidos
    ->get();

    // Preparar los datos para el gráfico
    $productos_nombres = $productos_mas_vendidos->pluck('nombre');
    $productos_ventas = $productos_mas_vendidos->pluck('total_ventas');

    // Pasar estos datos a la vista del dashboard
    return view('dashboard', compact('categorias', 'ventas', 'productos', 'clientes', 'productos_bajos_stock', 'productos_mas_vendidos', 'productos_nombres', 'productos_ventas'));
}
}
