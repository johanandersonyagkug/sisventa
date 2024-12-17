@extends('layouts.main') <!-- Aquí extiendes la plantilla -->

@section('content')
    <!-- Aquí inicias la sección de contenido -->

    <div class="stat-card2">

        <div class="card2">
            <!-- Campo de búsqueda y botón "Nuevo Cliente" alineados horizontalmente -->
            <div class="header-actions" style="display: flex; align-items: center; gap: 1rem;">
                <!-- Campo de búsqueda con botón dentro -->
                <form action="{{ route('clientes.index') }}" method="POST" style="flex: 1; display: flex; width: auto;">
                    <div class="input-group" style="display: flex; width: auto; position: relative; max-width: 300px;">
                        <input type="text" name="search" id="search" placeholder="Buscar cliente"
                            value="{{ request()->get('search') }} "
                            style="flex: 1; border-radius: 0.375rem; padding-right: 4rem; padding: 0.8rem; width: 100%; max-width: 250px;">
                        <button type="submit" class="btn3 primary"
                            style="position: absolute; right: 0; top: 0; bottom: 0; border-top-left-radius: 0; border-bottom-left-radius: 0; padding: 0 1rem;">Buscar</button>
                    </div>
                </form>

                <!-- Botón para agregar nuevo cliente -->
                <a href="{{ route('ventas.create') }}" class="btn accent" style="white-space: nowrap;"> Nueva Venta</a>
            </div>
        </div>

        <div class="card">
            <table class="table">
                <thead>
                    <tr>
                        <th>Numero</th>
                        <th>Cliente</th>
                        <th>Fecha</th>
                        <th>Total</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($ventas as $venta)
                        <tr>
                            <td>{{ $venta->numero }}</td>
                            <td>{{ $venta->cliente->nombre }}</td>
                            <td>{{ $venta->fecha }}</td>
                            <td>${{ number_format($venta->total, 2) }}</td>
                            <td>
                                
                             
                                    <a href="{{ route('ventas.edit', $venta) }}" class="btn btn-warning" title="Editar">
                                        <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACAAAAAgCAYAAABzenr0AAAACXBIWXMAAAsTAAALEwEAmpwYAAABR0lEQVR4nO2Wv0rEQBCHtwrO3IWZmE6sVUTRVxHfRcvZSXVXWGkjPoOVhXggPoJP4d/O0gNPScxBjEnMcZucB/lBIAzMft/sJiTGdOnyH8OKn01cyydgHKU9ATFeMAiofQExK6R4xhbHbHHCFkahwFo7AhLD4TrpsTgmhbfvexg1LyBZOLxyhHskxImExUk4DP3mBOQnPIh6u3GZBPdZ8YMV3+NnohkBMR4pXOXhofS3SOEprpPF05kGyqayoQacFW7WTwzUWq8opQ1ZuMLLFO5H/iZZeCyCV65XlsKGPFx6O3XgpetVhfMNc8CdCJDCQXrmz6vS3y468/itqD3Q7AJ4lAoMk8nF2yDFh78mdybAFs/T2iUrDLLbXjW5OwGF29+f13pwVwL3rHDHFi7I4jEpHNaFOxGYN8srwIv+JeNFCXTpYlrMF0mwjf7g5KniAAAAAElFTkSuQmCC" alt="pen-squared">
                                    </a>
                               
                                    
                           
                                <!-- Eliminar -->
                                <form action="{{ route('ventas.destroy', $venta) }}" method="POST" style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" class="btn btn-danger" title="Eliminar" id="open-modal-{{ $venta->id }}">
                                        <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABgAAAAYCAYAAADgdz34AAAACXBIWXMAAAsTAAALEwEAmpwYAAAAXElEQVR4nGNgGBHgIDt7w0EOjv8omJ29gzzD0A3iIA0PvAUgQJJiBtLVj1pAGIzGAUEwGkQEwWgQjYAgOsDO/ojUeuAAO/sjoi3Yz87uQYolB9jZH4H0EG3BkAIA4vvhcZ5VHzYAAAAASUVORK5CYII=" alt="delete">
                                    </button>
                                </form>
                            
                                <!-- Modal -->
<div class="modal" id="delete-modal-{{ $venta->id }}">
    <div class="modal-content">
        <div class="modal-header">
            <h2>Confirmar Eliminación</h2>
            <button class="close-button" data-id="{{ $venta->id }}">&times;</button>
        </div>
        <div class="modal-body">
            <p>¿Estás seguro de que deseas eliminar esta venta?</p>
        </div>
        <div class="modal-footer">
            <form action="{{ route('ventas.destroy', $venta) }}" method="POST">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger">Eliminar</button>
                <button type="button" class="btn btn-secondary cancel-button" data-id="{{ $venta->id }}">Cancelar</button>
            </form>
        </div>
    </div>
</div>

                            
                                <!-- Ver Detalles -->
                                <a href="{{ route('ventas.show', $venta) }}" class="btn btn-info btn-sm">
                                    <img src="img/ojo.png" alt="">
                                </a>
                                
                            </td>
                            <script>
                                document.addEventListener("DOMContentLoaded", () => {
                                    // Botones para abrir modales
                                    const openModalButtons = document.querySelectorAll("[id^='open-modal-']");
                                    const closeModalButtons = document.querySelectorAll(".close-button");
                                    const cancelButtons = document.querySelectorAll(".cancel-button");
                                
                                    // Abrir modal
                                    openModalButtons.forEach(button => {
                                        button.addEventListener("click", () => {
                                            const ventaId = button.id.split('-')[2];
                                            const modal = document.getElementById(`delete-modal-${ventaId}`);
                                            modal.classList.add("active");
                                        });
                                    });
                                
                                    // Cerrar modal con botón cerrar
                                    closeModalButtons.forEach(button => {
                                        button.addEventListener("click", () => {
                                            const ventaId = button.dataset.id;
                                            const modal = document.getElementById(`delete-modal-${ventaId}`);
                                            modal.classList.remove("active");
                                        });
                                    });
                                
                                    // Cerrar modal con botón cancelar
                                    cancelButtons.forEach(button => {
                                        button.addEventListener("click", () => {
                                            const ventaId = button.dataset.id;
                                            const modal = document.getElementById(`delete-modal-${ventaId}`);
                                            modal.classList.remove("active");
                                        });
                                    });
                                
                                    // Cerrar modal al hacer clic fuera de su contenido
                                    window.addEventListener("click", (event) => {
                                        const modals = document.querySelectorAll('.modal');
                                        modals.forEach(modal => {
                                            if (event.target === modal) {
                                                modal.classList.remove("active");
                                            }
                                        });
                                    });
                                });
                                
                                
                            </script>

                        </tr>
                    @endforeach
                </tbody>
            </table>

        </div>

        <div class="pagination">
            <!-- Botón de "Anterior" -->
            @if ($ventas->onFirstPage())
                <button class="btn2 primary" disabled>Anterior</button>
            @else
                <a href="{{ $ventas->previousPageUrl() }}" class="btn accent">Anterior</a>
            @endif
        
            @php
                $currentPage = $ventas->currentPage();
                $lastPage = $ventas->lastPage();
                $range = 1; // Mostrar 1 página antes y después de la página actual
            @endphp
        
            <!-- Mostrar la primera página si es necesario -->
            @if ($currentPage > 2)
                <a href="{{ $ventas->url(1) }}" class="btn secondary">1</a>
                @if ($currentPage > 3)
                    <span class="dots">...</span>
                @endif
            @endif
        
            <!-- Página anterior -->
            @if ($currentPage > 1)
                <a href="{{ $ventas->url($currentPage - 1) }}" class="btn secondary">{{ $currentPage - 1 }}</a>
            @endif
        
            <!-- Página actual -->
            <button class="btn accent">{{ $currentPage }}</button>
        
            <!-- Página siguiente -->
            @if ($currentPage < $lastPage)
                <a href="{{ $ventas->url($currentPage + 1) }}" class="btn secondary">{{ $currentPage + 1 }}</a>
            @endif
        
            <!-- Mostrar la última página si es necesario -->
            @if ($currentPage < $lastPage - 1)
                @if ($currentPage < $lastPage - 2)
                    <span class="dots">...</span>
                @endif
                <a href="{{ $ventas->url($lastPage) }}" class="btn secondary">{{ $lastPage }}</a>
            @endif
        
            <!-- Botón de "Siguiente" -->
            @if ($ventas->hasMorePages())
                <a href="{{ $ventas->nextPageUrl() }}" class="btn accent">Siguiente</a>
            @else
                <button class="btn2 accent" disabled>Siguiente</button>
            @endif
        </div>
        


        <!-- Mensaje de éxito -->
        @if (session('success'))
            <div id="success-message" class="alert alert-success fade-out">
                {{ session('success') }}
            </div>
        @endif

    </div>
@endsection
