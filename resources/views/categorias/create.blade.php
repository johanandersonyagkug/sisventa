@extends('layouts.main') <!-- Aquí extiendes la plantilla -->

@section('content') <!-- Aquí inicias la sección de contenido -->

<div class="cont">
    <!-- El action debe apuntar a la ruta del controlador 'store', y el método debe ser POST -->
    <form action="{{ route('categorias.store') }}" method="POST" class="form" style="display: flex; flex-direction: column; gap: 1.5rem;">
        @csrf <!-- Token de seguridad para formularios en Laravel -->

        <!-- Título del formulario -->
        <h2>Registrar nueva categoría</h2>

        <!-- Campo de texto -->
        <div class="input-group">
            <label for="nombre">Nombre</label>
            <input type="text" id="nombre" name="nombre" style="padding: 0.8rem;" placeholder="" required>
        </div>

        <!-- Área de texto -->
        <div class="input-group">
            <label for="descripcion">Descripción</label>
            <textarea id="descripcion" name="descripcion" rows="4" style="padding: 0.8rem;" placeholder=""></textarea>
        </div>

        <!-- Botón de envío -->
        <div style="text-align: right;">
            <button type="submit" class="btn accent">Guardar</button>
        </div>
    </form>
</div>

@endsection
