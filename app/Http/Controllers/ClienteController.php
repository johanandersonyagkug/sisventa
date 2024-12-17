<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use Illuminate\Http\Request;

class ClienteController extends Controller
{
    // Muestra la lista de clientes
    public function index()
    {
        $clientes = Cliente::paginate(3);  // Obtener todos los clientes paginados
        return view('clientes.index', compact('clientes'));
    }

    // Muestra el formulario para crear un nuevo cliente
    public function create()
    {
        return view('clientes.create');
    }

    // Almacena un nuevo cliente
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'apellido_paterno' => 'required|string|max:255',
            'apellido_materno' => 'nullable|string|max:255',
            'telefono' => 'required|string|max:9|min:9',
            'direccion' => 'required|string|max:255',
            'dni' => 'required|string|max:8|min:8|unique:clientes,dni',
        ]);
    
        try {
            Cliente::create($request->all());
            return redirect()->route('clientes.index')->with('success', 'Cliente creado exitosamente.');
        } catch (\Exception $e) {
            return back()->with('error', 'Error al guardar el cliente: ' . $e->getMessage());
        }
    }
    

    // Muestra el formulario para editar un cliente existente
    public function edit($id)
    {
        // Buscar el cliente por su ID
        $cliente = Cliente::findOrFail($id);
        return view('clientes.edit', compact('cliente'));
    }

    // Actualiza los datos de un cliente existente
    public function update(Request $request, $id)
    {
        // Validación de los datos del cliente
        $request->validate([
            'nombre' => 'required|string|max:255',
            'apellido_paterno' => 'required|string|max:255',
            'apellido_materno' => 'nullable|string|max:255',
            'telefono' => 'required|string|max:20',
            'direccion' => 'required|string|max:255',
            'dni' => 'required|string|max:8|min:8|unique:clientes,dni,' . $id, // Validar DNI pero ignorar el cliente actual
        ]);

        // Buscar el cliente por su ID
        $cliente = Cliente::findOrFail($id);

        // Actualizar los datos del cliente
        $cliente->update([
            'nombre' => $request->nombre,
            'apellido_paterno' => $request->apellido_paterno,
            'apellido_materno' => $request->apellido_materno,
            'telefono' => $request->telefono,
            'direccion' => $request->direccion,
            'dni' => $request->dni, // Actualizar el DNI
        ]);

        // Redirigir a la lista de clientes con un mensaje de éxito
        return redirect()->route('clientes.index')->with('success', 'Cliente actualizado exitosamente.');
    }

    // Eliminar un cliente
    public function destroy($id)
    {
        // Buscar el cliente por su ID
        $cliente = Cliente::findOrFail($id);

        // Eliminar el cliente
        $cliente->delete();

        // Redirigir a la lista de clientes con un mensaje de éxito
        return redirect()->route('clientes.index')->with('success', 'Cliente eliminado exitosamente.');
    }
}
