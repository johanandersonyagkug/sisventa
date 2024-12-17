@extends('layouts.main')

@section('content')
    <div class="cont">
        <h3 >Detalles de Venta #{{ $venta->numero }}</h3>
        <div class="card">
        <div class="input-group-container">
            <!-- Campo para Cliente -->
            <div class="input-group">
                <label for="cliente">Cliente</label>
                <input type="text" id="cliente" class="form-control" value="{{ $venta->cliente->nombre }}" readonly>
            </div>

            <!-- Campo para Fecha -->
            <div class="input-group">
                <label for="fecha">Fecha</label>
                <input type="text" id="fecha" class="form-control" value="{{ $venta->fecha }}" readonly>
            </div>

            <!-- Campo para Total -->
            <div class="input-group">
                <label for="total">Total</label>
                <input type="text" id="total" class="form-control" value="{{ number_format($venta->total, 2) }}" readonly>
            </div>
        </div>
        </div>
        <!-- Productos de la venta en tabla -->
        <h3>Productos</h3>
        <div class="card">
          
            <div class="table">
                <table>
                    <thead>
                        <tr>
                            <th>Producto</th>
                            <th>Precio Unitario</th>
                            <th>Cantidad</th>
                            <th>Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($venta->productos as $producto)
                            <tr>
                                <td>{{ $producto->nombre }}</td>
                                <td>${{ number_format($producto->precio, 2) }}</td>
                                <td>{{ $producto->pivot->cantidad }}</td>
                                <td>${{ number_format($producto->pivot->cantidad * $producto->precio, 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

    </div>
@endsection

