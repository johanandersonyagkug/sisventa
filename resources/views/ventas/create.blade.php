@extends('layouts.main')

@section('content')
    <div class="cont">
        <h1 class="mb-4">Crear Nueva Venta</h1>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('ventas.store') }}" method="POST" id="ventaForm">
            @csrf
            <div class="input-group-container">
                <div class="input-group">
                    <label for="cliente_id">Cliente</label>
                    <select name="cliente_id" id="cliente_id" class="form-control" required>
                        <option value="">Seleccionar Cliente</option>
                        @foreach ($clientes as $cliente)
                            <option value="{{ $cliente->id }}">{{ $cliente->nombre }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="input-group">
                    <label for="fecha">Fecha</label>
                    <input type="date" id="fecha" class="form-control" readonly>
                </div>
                
                <script>
                    // Obtener la fecha actual
                    const today = new Date();
                    
                    // Formatear la fecha en el formato adecuado para el campo de tipo "date"
                    const formattedDate = today.toISOString().split('T')[0];
                    
                    // Asignar la fecha al campo de entrada
                    document.getElementById('fecha').value = formattedDate;
                </script>
                    
            </div>
            <div class="card mb-3">

                <div class="card-header">Agregar Producto</div>


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
                    <div id="mensajeStock" class="d-none" role="alert"></div>
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
                        <tbody></tbody>
                    </table>
                </div>
            </div>

            <!-- Campos ocultos para los productos -->
            <div id="productosHiddenFields"></div>


            <div class="input-group">
                <div class="pagination">
                    <label>Total de la Venta</label>
                    <input type="text" id="totalVenta" class="" readonly>
                </div>
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
            const mensajeStock = document.getElementById('mensajeStock');

            let totalVenta = 0;
            let productoIndex = 0;

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
                    mostrarMensajeStock(
                        `La cantidad ingresada (${cantidad}) excede el stock disponible (${stock}).`);
                    return;
                }

                // Verificar si el producto ya está en la tabla
                const filaExistente = Array.from(tablaProductos.children).find(row => row.dataset
                    .productoId === productoId);

                if (filaExistente) {
                    const cantidadActual = parseInt(filaExistente.querySelector('.cantidad').textContent);
                    const nuevaCantidad = cantidadActual + cantidad;

                    if (nuevaCantidad > stock) {
                        mostrarMensajeStock(
                            `La cantidad total (${nuevaCantidad}) excede el stock disponible (${stock}).`
                        );
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

                    // Limpiar campos
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

                // Limpiar campos
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

            function mostrarMensajeStock(mensaje) {
                // Mostrar el mensaje
                mensajeStock.textContent = mensaje;
                mensajeStock.classList.remove('d-none');
                
                // Asegurarse de que no haya un mensaje anterior visible antes de iniciar el temporizador
                clearTimeout(mensajeStockTimeout);
                
                // Establecer el temporizador para que el mensaje desaparezca después de 2 segundos
                mensajeStockTimeout = setTimeout(() => {
                    mensajeStock.classList.add('d-none');
                }, 2000); // 2000 milisegundos = 2 segundos
            }
            
            
        });
    </script>
@endsection
