<?php

namespace App\Http\Controllers;

use App\Models\Venta;
use App\Models\Producto;
use App\Models\Cliente;
use App\Models\ProductoVenta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VentaController extends Controller
{
    public function index()
    {
        // Paginación con 10 ventas por página, puedes cambiar el número si lo deseas
        $ventas = Venta::with(['cliente', 'productos'])->paginate(3);

        // Pasamos la variable 'ventas' a la vista, con la paginación
        return view('ventas.index', compact('ventas'));
    }


    // Show form to create a new sale
    public function create()
    {
        $clientes = Cliente::all();
        $productos = Producto::all();
        return view('ventas.create', compact('clientes', 'productos'));
    }

    // Store a new sale
    public function store(Request $request)
    {
        $request->validate([
            'cliente_id' => 'required|exists:clientes,id',
            'productos' => 'required|array',
            'productos.*.id' => 'required|exists:productos,id',
            'productos.*.cantidad' => 'required|integer|min:1'
        ]);

        try {
            DB::beginTransaction();

            // Crear la venta
            $venta = Venta::create([
                'cliente_id' => $request->cliente_id,
                'numero' => $this->generarNumeroVenta(),
                'total' => 0,
                'fecha' => now()
            ]);

            $total = 0;

            // Procesar productos y actualizar stock
            foreach ($request->productos as $producto) {
                $productoModel = Producto::findOrFail($producto['id']);

                // Validar si hay suficiente stock
                if ($producto['cantidad'] > $productoModel->stock) {
                    throw new \Exception("El producto '{$productoModel->nombre}' no tiene suficiente stock disponible.");
                }

                // Reducir stock del producto
                $productoModel->decrement('stock', $producto['cantidad']);

                // Calcular subtotal
                $subtotal = $productoModel->precio * $producto['cantidad'];
                $total += $subtotal;

                // Asociar producto con la venta
                $venta->productos()->attach($producto['id'], [
                    'cantidad' => $producto['cantidad']
                ]);
            }

            // Actualizar el total de la venta
            $venta->update(['total' => $total]);

            DB::commit();

            return redirect()->route('ventas.index')->with('success', 'Venta creada exitosamente');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error al crear la venta: ' . $e->getMessage());
        }
    }

    // Show form to edit an existing sale
    public function edit(Venta $venta)
    {
        $clientes = Cliente::all();
        $productos = Producto::all();
        $venta->load('productos'); // Cargar los productos asociados
        return view('ventas.edit', compact('venta', 'clientes', 'productos'));
    }

    // Update an existing sale
    public function update(Request $request, Venta $venta)
    {
        $request->validate([
            'cliente_id' => 'required|exists:clientes,id',
            'productos' => 'required|array',
            'productos.*.id' => 'required|exists:productos,id',
            'productos.*.cantidad' => 'required|integer|min:1'
        ]);

        try {
            DB::beginTransaction();

            // Actualizar la venta
            $venta->update([
                'cliente_id' => $request->cliente_id,
                'fecha' => now()
            ]);

            // Revertir el stock de los productos anteriores
            foreach ($venta->productos as $productoVenta) {
                $producto = Producto::findOrFail($productoVenta->id);
                $producto->increment('stock', $productoVenta->pivot->cantidad);
            }

            // Limpiar productos asociados antes de agregar los nuevos
            $venta->productos()->detach();

            $total = 0;

            // Procesar los nuevos productos
            foreach ($request->productos as $producto) {
                $productoModel = Producto::findOrFail($producto['id']);

                // Validar si hay suficiente stock
                if ($producto['cantidad'] > $productoModel->stock) {
                    throw new \Exception("El producto '{$productoModel->nombre}' no tiene suficiente stock disponible.");
                }

                // Reducir stock del producto
                $productoModel->decrement('stock', $producto['cantidad']);

                // Calcular subtotal
                $subtotal = $productoModel->precio * $producto['cantidad'];
                $total += $subtotal;

                // Asociar producto con la venta
                $venta->productos()->attach($producto['id'], [
                    'cantidad' => $producto['cantidad']
                ]);
            }

            // Actualizar el total de la venta
            $venta->update(['total' => $total]);

            DB::commit();

            return redirect()->route('ventas.index')->with('success', 'Venta actualizada exitosamente');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error al actualizar la venta: ' . $e->getMessage());
        }
    }


    // Delete a sale
    public function destroy(Venta $venta)
    {
        try {
            DB::beginTransaction();

            // Revertir el stock de los productos asociados a la venta
            foreach ($venta->productos as $productoVenta) {
                $producto = Producto::findOrFail($productoVenta->id);
                $producto->increment('stock', $productoVenta->pivot->cantidad);
            }

            // Eliminar los productos asociados
            $venta->productos()->detach();

            // Eliminar la venta
            $venta->delete();

            DB::commit();

            return redirect()->route('ventas.index')->with('success', 'Venta eliminada exitosamente');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error al eliminar la venta: ' . $e->getMessage());
        }
    }


    // Show sale details
    public function show(Venta $venta)
    {
        $venta->load(['cliente', 'productos']);
        return view('ventas.show', compact('venta'));
    }

    // Generate unique sale number
    private function generarNumeroVenta()
    {
        $ultimaVenta = Venta::orderBy('numero', 'desc')->first();
        return $ultimaVenta ? $ultimaVenta->numero + 1 : 1;
    }
}
