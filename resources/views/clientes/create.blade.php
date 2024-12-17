<!-- resources/views/clientes/create.blade.php -->

@extends('layouts.main')

@section('content')
    <div class="cont">
        <form action="{{ route('clientes.store') }}" method="POST">
            @csrf
            <h2>Registrar nuevo cliente</h2>
            <div class="input-group-container">
                <div class="input-group">
                    <label for="dni">DNI</label>
                    <input type="text" id="dni" style="padding: 0.8rem;" name="dni"
                        class="form-control @error('dni') is-invalid @enderror" value="{{ old('dni') }}" required>
                    @error('dni')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="input-group">
                    <label for="nombre">Nombre</label>
                    <input type="text" id="nombre" style="padding: 0.8rem;" name="nombre"
                        class="form-control @error('nombre') is-invalid @enderror" value="{{ old('nombre') }}" required>
                    @error('nombre')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

               
            </div>
            <div class="input-group-container">
                <div class="input-group">
                    <label for="apellido_paterno">Apellido Paterno</label>
                    <input type="text" id="apellido_paterno" style="padding: 0.8rem;" name="apellido_paterno"
                        class="form-control @error('apellido_paterno') is-invalid @enderror"
                        value="{{ old('apellido_paterno') }}" required>
                    @error('apellido_paterno')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="input-group">
                    <label for="apellido_materno">Apellido Materno</label>
                    <input type="text" id="apellido_materno" style="padding: 0.8rem;" name="apellido_materno"
                        class="form-control @error('apellido_materno') is-invalid @enderror"
                        value="{{ old('apellido_materno') }}">
                    @error('apellido_materno')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="input-group">
                    <label for="telefono">Teléfono</label>
                    <input type="text" id="telefono" style="padding: 0.8rem;" name="telefono"
                        class="form-control @error('telefono') is-invalid @enderror" value="{{ old('telefono') }}" required>
                    @error('telefono')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

              
              
            </div>
            <div class="input-group">
                <label for="direccion">Dirección</label>
                <textarea id="direccion" name="direccion" class="form-control @error('direccion') is-invalid @enderror" rows="4"
                    required>{{ old('direccion') }}</textarea>
                @error('direccion')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div style="text-align: right;">
                <button type="submit" class="btn accent">Guardar</button>
            </div>
        </form>
    </div>
@endsection
