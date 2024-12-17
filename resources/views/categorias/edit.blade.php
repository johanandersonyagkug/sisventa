@extends('layouts.main') <!-- Aquí extiendes la plantilla -->

@section('content') <!-- Aquí inicias la sección de contenido -->

<div class="cont">
    <!-- El action debe apuntar a la ruta del controlador 'update', y el método debe ser POST con @method('PUT') -->
    <form action="{{ route('categorias.update', $categoria->id) }}" method="POST" class="form" style="display: flex; flex-direction: column; gap: 1.5rem;">
        @csrf <!-- Token de seguridad para formularios en Laravel -->
        @method('PUT') <!-- Indica que el método real es PUT -->

        <!-- Título del formulario -->
        <h2>Editar categoría</h2>

        <!-- Campo de texto para el nombre -->
        <div class="input-group">
            <label for="nombre">Nombre</label>
            <input type="text" id="nombre" name="nombre" style="padding: 0.8rem;" value="{{ old('nombre', $categoria->nombre) }}" required>
        </div>

        <!-- Área de texto para la descripción -->
        <div class="input-group">
            <label for="descripcion">Descripción</label>
            <textarea id="descripcion" name="descripcion" rows="4" style="padding: 0.8rem;">{{ old('descripcion', $categoria->descripcion) }}</textarea>
        </div>

        <!-- Botón de envío -->
        <div style="text-align: right;">
            <button type="submit" class="btn2 primary">Actualizar</button>
        </div>
    </form>
</div>

@endsection
