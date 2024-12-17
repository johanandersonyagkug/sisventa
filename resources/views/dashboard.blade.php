@extends('layouts.main')

@section('content')
<div class="dashboard">
    <div class="stats-grid">
        <div class="stat-card">
            <h3>Categorías</h3>
            <div class="value">{{ $categorias }}</div>
        </div>

        <div class="stat-card">
            <h3>Ventas</h3>
            <div class="value">${{ number_format($ventas, 2) }}</div>
        </div>

        <div class="stat-card">
            <h3>Productos</h3>
            <div class="value">{{ $productos }}</div>
        </div>

        <div class="stat-card">
            <h3>Clientes</h3>
            <div class="value">{{ $clientes }}</div>
        </div>
    </div>

    <div class="chart-grid">
        <div class="chart-card">
            <h3>Productos más vendidos</h3>
            <canvas id="barChart"></canvas> 
        </div>

        <div class="chart-card">
            <h3>Productos con Stock Bajo</h3>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Precio</th>
                        <th>Stock</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($productos_bajos_stock as $producto)
                        <tr>
                            <td>{{ $producto->id }}</td>
                            <td>{{ $producto->nombre }}</td>
                            <td>${{ number_format($producto->precio, 2) }}</td>
                            <td><span class="status rejected">{{ $producto->stock }}</span></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('barChart').getContext('2d');
    const barChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: @json($productos_nombres), // Nombres de los productos
            datasets: [{
                label: 'Unidades Vendidas',
                data: @json($productos_ventas), // Ventas de cada producto
                backgroundColor: 'rgba(54, 162, 235, 0.2)', // Color de las barras
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1
                    }
                }
            }
        }
    });
</script>
@endsection