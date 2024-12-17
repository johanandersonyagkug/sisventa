@extends('layouts.main')

@section('content')
    <div class="cont">
        <h1 class="mb-4">Editar Venta #{{ $venta->numero }}</h1>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('ventas.update', $venta->id) }}" method="POST" id="ventaForm">
            @csrf
            @method('PUT') <!-- Indica que es una solicitud de actualización -->
            <div class="input-group-container">
                <div class="input-group">
                    <label for="cliente_id">Cliente</label>
                    <select name="cliente_id" id="cliente_id" class="form-control" required>
                        <option value="">Seleccionar Cliente</option>
                        @foreach ($clientes as $cliente)
                            <option value="{{ $cliente->id }}" {{ $cliente->id == $venta->cliente_id ? 'selected' : '' }}>
                                {{ $cliente->nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="card mb-3">
                <div class="card-header">Modificar Productos</div>

                <div class="input-group-container">
                    <div class="input-group">
                        <label for="producto_id">Producto</label>
                        <select id="producto_id" class="form-control">
                            <option value="">Seleccionar Producto</option>
                            @foreach ($productos as $producto)
                                <option value="{{ $producto->id }}" data-precio="{{ $producto->precio }}"
                                    data-stock="{{ $producto->stock }}">
                                    {{ $producto->nombre }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="input-group">
                        <label for="precio">Precio</label>
                        <input type="text" id="precio" class="form-control" readonly>
                    </div>
                    <div class="input-group">
                        <label for="cantidad">Cantidad</label>
                        <input type="number" id="cantidad" class="form-control" min="1">
                    </div>
                </div>

                <div class="input-group-container">
                    <div class="col-md-2 d-flex align-items-end">
                        <button type="button" id="agregarProducto" class="btn secondary w-100">Agregar</button>
                    </div>
                </div>
            </div>

            <div class="card-header">Productos Agregados</div>
            <div class="card mb-3">
                <div class="card-body">
                    <table class="table" id="tablaProductos">
                        <thead>
                            <tr>
                                <th>Producto</th>
                                <th>Precio</th>
                                <th>Cantidad</th>
                                <th>Subtotal</th>
                                <th>Acción</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($venta->productos as $productoVenta)
                            <tr data-producto-id="{{ $productoVenta->id }}">
                                <td>{{ $productoVenta->nombre }}</td>
                                <td>$ {{ number_format($productoVenta->precio, 2) }}</td>
                                <td class="cantidad">{{ $productoVenta->pivot->cantidad }}</td>
                                <td class="subtotal">$ {{ number_format($productoVenta->precio * $productoVenta->pivot->cantidad, 2) }}</td>
                                <td>
                                    <button type="button" class="btn btn-danger btn-sm eliminarProducto">Eliminar</button>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div id="productosHiddenFields"></div>

            <div class="input-group">
                <label>Total de la Venta</label>
                <input type="text" id="totalVenta" class="" readonly value="{{ number_format($venta->total, 2) }}">
            </div>

            <button type="submit" class="btn accent">Guardar Venta</button>

        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const productoSelect = document.getElementById('producto_id');
            const precioInput = document.getElementById('precio');
            const cantidadInput = document.getElementById('cantidad');
            const agregarProductoBtn = document.getElementById('agregarProducto');
            const tablaProductos = document.getElementById('tablaProductos').querySelector('tbody');
            const productosHiddenFields = document.getElementById('productosHiddenFields');
            const totalVentaInput = document.getElementById('totalVenta');

            let totalVenta = parseFloat(totalVentaInput.value);

            // Verifica si ya hay productos preexistentes y mantén la tabla actualizada
            let productoIndex = {{ count($venta->productos) }};

            // Cargar precio cuando se selecciona un producto
            productoSelect.addEventListener('change', function() {
                const precio = productoSelect.options[productoSelect.selectedIndex].dataset.precio;
                precioInput.value = precio ? parseFloat(precio).toFixed(2) : '';
            });

            // Agregar producto a la tabla
            agregarProductoBtn.addEventListener('click', function() {
                const productoId = productoSelect.value;
                const productoNombre = productoSelect.options[productoSelect.selectedIndex].text;
                const precio = parseFloat(precioInput.value);
                const cantidad = parseInt(cantidadInput.value);
                const stock = parseInt(productoSelect.options[productoSelect.selectedIndex].dataset.stock);

                if (!productoId || isNaN(precio) || isNaN(cantidad) || cantidad <= 0) {
                    alert('Por favor, selecciona un producto y asegúrate de ingresar una cantidad válida.');
                    return;
                }

                if (cantidad > stock) {
                    alert(`La cantidad ingresada (${cantidad}) excede el stock disponible (${stock}).`);
                    return;
                }

                // Verificar si el producto ya está en la tabla
                const filaExistente = Array.from(tablaProductos.children).find(row => row.dataset.productoId === productoId);

                if (filaExistente) {
                    const cantidadActual = parseInt(filaExistente.querySelector('.cantidad').textContent);
                    const nuevaCantidad = cantidadActual + cantidad;

                    if (nuevaCantidad > stock) {
                        alert(`La cantidad total (${nuevaCantidad}) excede el stock disponible (${stock}).`);
                        return;
                    }

                    // Actualizar cantidad y subtotal
                    filaExistente.querySelector('.cantidad').textContent = nuevaCantidad;
                    const nuevoSubtotal = nuevaCantidad * precio;
                    filaExistente.querySelector('.subtotal').textContent = `$${nuevoSubtotal.toFixed(2)}`;

                    // Actualizar total de la venta
                    totalVenta += cantidad * precio;
                    totalVentaInput.value = totalVenta.toFixed(2);

                    // Actualizar campos ocultos
                    const hiddenCantidad = productosHiddenFields.querySelector(
                        `input[name="productos[${filaExistente.dataset.index}][cantidad]"]`);
                    hiddenCantidad.value = nuevaCantidad;

                    limpiarCampos();
                    return;
                }

                const subtotal = precio * cantidad;

                // Crear fila en la tabla
                const fila = document.createElement('tr');
                fila.dataset.productoId = productoId;
                fila.dataset.index = productoIndex;
                fila.innerHTML = `
                    <td>${productoNombre}</td>
                    <td>$${precio.toFixed(2)}</td>
                    <td class="cantidad">${cantidad}</td>
                    <td class="subtotal">$${subtotal.toFixed(2)}</td>
                    <td>
                        <button type="button" class="btn btn-danger btn-sm eliminarProducto">Eliminar</button>
                    </td>
                `;

                // Añadir fila a la tabla
                tablaProductos.appendChild(fila);

                // Crear campos ocultos para enviar al servidor
                const hiddenId = document.createElement('input');
                hiddenId.type = 'hidden';
                hiddenId.name = `productos[${productoIndex}][id]`;
                hiddenId.value = productoId;

                const hiddenCantidad = document.createElement('input');
                hiddenCantidad.type = 'hidden';
                hiddenCantidad.name = `productos[${productoIndex}][cantidad]`;
                hiddenCantidad.value = cantidad;

                const hiddenPrecio = document.createElement('input');
                hiddenPrecio.type = 'hidden';
                hiddenPrecio.name = `productos[${productoIndex}][precio]`;
                hiddenPrecio.value = precio;

                productosHiddenFields.appendChild(hiddenId);
                productosHiddenFields.appendChild(hiddenCantidad);
                productosHiddenFields.appendChild(hiddenPrecio);

                // Incrementar el índice de producto
                productoIndex++;

                // Actualizar total de la venta
                totalVenta += subtotal;
                totalVentaInput.value = totalVenta.toFixed(2);

                limpiarCampos();

                // Eliminar producto de la tabla
                fila.querySelector('.eliminarProducto').addEventListener('click', function() {
                    totalVenta -= subtotal;
                    totalVentaInput.value = totalVenta.toFixed(2);
                    fila.remove();
                    hiddenId.remove();
                    hiddenCantidad.remove();
                    hiddenPrecio.remove();
                });
            });

            function limpiarCampos() {
                productoSelect.value = '';
                precioInput.value = '';
                cantidadInput.value = '';
            }
        });
    </script>
@endsection
