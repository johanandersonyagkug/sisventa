<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use Illuminate\Http\Request;


class CategoriaController extends Controller
{
 
    public function index()
    {
        $categorias = Categoria::paginate(3); // Cambia el número 10 por la cantidad de elementos que quieras mostrar por página
        return view('categorias.index', compact('categorias'));
    }
    

  
    public function create()
    {
        return view('categorias.create'); // Muestra el formulario de creación
    }

   
    public function store(Request $request)
    {
        // Validar los datos del formulario
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
        ]);

        // Crear la nueva categoría
        Categoria::create($validated);

        return redirect()->route('categorias.index')->with('success', 'Categoría creada exitosamente');
    }

  
    public function edit(Categoria $categoria)
    {
        return view('categorias.edit', compact('categoria'));
    }

  
    public function update(Request $request, Categoria $categoria)
    {
        // Validar los datos del formulario
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
        ]);

        // Actualizar la categoría
        $categoria->update($validated);

        return redirect()->route('categorias.index')->with('success', 'Categoría actualizada exitosamente');
    }

    
    public function destroy(Categoria $categoria)
    {
        // Eliminar la categoría
        $categoria->delete();

        return redirect()->route('categorias.index')->with('success', 'Categoría eliminada exitosamente');
    }
}
