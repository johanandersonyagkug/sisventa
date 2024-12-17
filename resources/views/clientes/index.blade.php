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
                <a href="{{ route('clientes.create') }}" class="btn accent" style="white-space: nowrap;">Nuevo Cliente</a>
            </div>
        </div>

        <div class="card">
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>DNI</th>
                        <th>Nombre</th>
                        <th>Teléfono</th>
                        <th>Dirección</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($clientes as $cliente)
                        <tr>
                            <td>{{ $cliente->id }}</td>
                            <td>{{ $cliente->dni }}</td>
                            <td>{{ $cliente->nombre }} {{ $cliente->apellido_paterno }} {{ $cliente->apellido_materno }}</td>
                            <td>{{ $cliente->telefono }}</td>
                            <td>{{ $cliente->direccion }}</td>
                            <td>
                                <!-- Botón de editar -->
                                <a href="{{ route('clientes.edit', $cliente->id) }}" class="btn btn-warning"
                                    title="Editar">
                                     <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACAAAAAgCAYAAABzenr0AAAACXBIWXMAAAsTAAALEwEAmpwYAAABR0lEQVR4nO2Wv0rEQBCHtwrO3IWZmE6sVUTRVxHfRcvZSXVXWGkjPoOVhXggPoJP4d/O0gNPScxBjEnMcZucB/lBIAzMft/sJiTGdOnyH8OKn01cyydgHKU9ATFeMAiofQExK6R4xhbHbHHCFkahwFo7AhLD4TrpsTgmhbfvexg1LyBZOLxyhHskxImExUk4DP3mBOQnPIh6u3GZBPdZ8YMV3+NnohkBMR4pXOXhofS3SOEprpPF05kGyqayoQacFW7WTwzUWq8opQ1ZuMLLFO5H/iZZeCyCV65XlsKGPFx6O3XgpetVhfMNc8CdCJDCQXrmz6vS3y468/itqD3Q7AJ4lAoMk8nF2yDFh78mdybAFs/T2iUrDLLbXjW5OwGF29+f13pwVwL3rHDHFi7I4jEpHNaFOxGYN8srwIv+JeNFCXTpYlrMF0mwjf7g5KniAAAAAElFTkSuQmCC"
                                        alt="pen-squared">
                                </a>

                                <!-- Formulario de eliminación con modal -->
                                <form style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" title="Eliminar" id="open-modal">
                                       <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABgAAAAYCAYAAADgdz34AAAACXBIWXMAAAsTAAALEwEAmpwYAAAAXElEQVR4nGNgGBHgIDt7w0EOjv8omJ29gzzD0A3iIA0PvAUgQJJiBtLVj1pAGIzGAUEwGkQEwWgQjYAgOsDO/ojUeuAAO/sjoi3Yz87uQYolB9jZH4H0EG3BkAIA4vvhcZ5VHzYAAAAASUVORK5CYII=" 
                                            alt="delete">
                                    </button>
                                </form>

                                <!-- Modal de confirmación de eliminación -->
                                <div class="modal" id="delete-modal">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h2>Confirmar Eliminación</h2>
                                            <button class="close-button" id="close-modal">&times;</button>
                                        </div>
                                        <div class="modal-body">
                                            <p>¿Estás seguro de que deseas eliminar "{{ $cliente->nombre }}"?</p>
                                        </div>
                                        <div class="modal-footer">
                                            <form action="{{ route('clientes.destroy', $cliente->id) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn destructive">Eliminar</button>
                                                <button type="button" class="btn secondary" id="cancel-button">Cancelar</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>

                                <script>
                                    document.addEventListener("DOMContentLoaded", () => {
                                        const openModal = document.getElementById("open-modal");
                                        const closeModal = document.getElementById("close-modal");
                                        const cancelButton = document.getElementById("cancel-button");
                                        const modal = document.getElementById("delete-modal");

                                        const toggleModal = () => {
                                            modal.classList.toggle("active");
                                        };

                                        openModal.addEventListener("click", toggleModal);
                                        closeModal.addEventListener("click", toggleModal);
                                        cancelButton.addEventListener("click", toggleModal);

                                        // Close modal when clicking outside the modal content
                                        window.addEventListener("click", (event) => {
                                            if (event.target === modal) {
                                                toggleModal();
                                            }
                                        });
                                    });
                                </script>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

        </div>

        <!-- Paginación -->
        <div class="pagination">
            @if ($clientes->onFirstPage())
                <button class="btn2 primary" disabled>Anterior</button>
            @else
                <a href="{{ $clientes->previousPageUrl() }}" class="btn primary">Anterior</a>
            @endif

            @foreach ($clientes->links()->elements[0] as $page => $url)
                @if ($page == $clientes->currentPage())
                    <button class="btn accent">{{ $page }}</button>
                @else
                    <a href="{{ $url }}" class="btn secondary">{{ $page }}</a>
                @endif
            @endforeach

            @if ($clientes->hasMorePages())
                <a href="{{ $clientes->nextPageUrl() }}" class="btn primary">Siguiente</a>
            @else
                <button class="btn2 primary" disabled>Siguiente</button>
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
