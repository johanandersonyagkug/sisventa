<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use App\Models\Categoria;
use Illuminate\Http\Request;

class ProductoController extends Controller
{
    // Muestra una lista de productos
    public function index()
    {
        $productos = Producto::paginate(3);
        return view('productos.index', compact('productos'));
    }
    public function create()
    {
        $categorias = Categoria::all(); 
        return view('productos.create', compact('categorias'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'required|string',
            'precio' => 'required|numeric|min:0',
            'stock' => 'required|numeric|min:1', // Asegúrate de que se valida correctamente
            'categoria_id' => 'required|exists:categorias,id',
        ]);
    
        Producto::create([
            'nombre' => $request->nombre,
            'descripcion' => $request->descripcion,
            'precio' => $request->precio,
            'stock' => $request->stock, // Asegúrate de pasar el stock aquí
            'categoria_id' => $request->categoria_id,
        ]);
    
        return redirect()->route('productos.index')->with('success', 'Producto creado exitosamente.');
    }
    public function edit($id)
{
    // Obtener el producto por su ID
    $producto = Producto::findOrFail($id);
    
    // Obtener todas las categorías para el campo de selección
    $categorias = Categoria::all();
    
    // Retornar la vista de edición con el producto y las categorías
    return view('productos.edit', compact('producto', 'categorias'));
}

    public function update(Request $request, $id)
    {
        // Validar los datos del formulario
        $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'required|string',
            'precio' => 'required|numeric|min:0',
            'stock' => 'required|numeric|min:1',
            'categoria_id' => 'required|exists:categorias,id',
        ]);
    
        // Encontrar el producto por su ID
        $producto = Producto::findOrFail($id);
    
        // Actualizar el producto con los nuevos valores
        $producto->update([
            'nombre' => $request->nombre,
            'descripcion' => $request->descripcion,
            'precio' => $request->precio,
            'stock' => $request->stock,
            'categoria_id' => $request->categoria_id,
        ]);
    
        // Redirigir a la lista de productos con un mensaje de éxito
        return redirect()->route('productos.index')->with('success', 'Producto actualizado exitosamente.');
    }
    public function destroy($id)
{
    // Encontrar el producto por su ID
    $producto = Producto::findOrFail($id);

    // Eliminar el producto
    $producto->delete();

    // Redirigir a la lista de productos con un mensaje de éxito
    return redirect()->route('productos.index')->with('success', 'Producto eliminado exitosamente.');
}

}
