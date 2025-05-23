

<!-- AVISO DE PRODUCTOS CON BAJO STOCK DE BULTOS -->
<div class="container my-4">
    <div class="alert alert-warning" id="aviso-bajo-stock" style="display:none;"></div>
</div>

<!-- TOP DE PRODUCTOS CON BAJO STOCK POR BULTO -->
<div class="container my-4">
    <div class="card">
        <div class="card-header bg-danger text-white">Top productos con bajo stock por bulto</div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-bordered table-hover mb-0" id="tabla-bajo-bulto">
                    <thead class="table-light">
                        <tr>
                            <th>Producto</th>
                            <th>Código</th>
                            <th>Cant. por bulto</th>
                            <th>Bultos min aviso</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Aquí irán los siguientes cuadros de ventas, top de productos vendidos y calendario -->

@include('negocio._ventas_resumen')
@include('negocio._top_productos_vendidos')
@include('negocio._ventas_fecha')
@include('negocio._solo_ventas_fecha')
@include('negocio._top_productos_vendidos_fecha')



<div class="container d-flex justify-content-center align-items-center">
    <div class="text-center w-100" style="max-width: 400px;">
        <div class="card-body">
            <h4 class="card-title fw-bold">{{ Auth::user()->nombre }}</h4>
            <p class="card-subtitle text-muted mb-3">{{ Auth::user()->email }}</p>
            @php
                use Carbon\Carbon;

                $fechaVencimiento = Carbon::parse(Auth::user()->licencia_expires_at);
                $diasRestantes = now()->diffInDays($fechaVencimiento, false);
                $diasRestantes = (int) $diasRestantes;
                $dia = $diasRestantes > 1 ? 's' : '';
                $mensaje = "Licencia válida por $diasRestantes día$dia";
                if ($diasRestantes <= 0) {
                    $mensaje = 'Su licencia vence hoy a las 00 hrs';
                }
            @endphp
            <div class="border rounded p-3 mb-3">
                <p class="mb-1 fw-semibold">{{ $mensaje }}</p>
                <small class="text-muted">Vencimiento el {{ $fechaVencimiento->format('d/m/Y') }}</small>
            </div>

            <p class="text-muted small mb-0">
                © 2025 {{ Auth::user()->nombre }}. Contacto: <a
                    href="mailto:aramayo420@email.com">aramayo420@email.com</a><br>
                para renovación de licencia o nuevas compras.
            </p>
        </div>
    </div>
</div>


<script>
    // Lógica para cargar productos y mostrar avisos/top bajo stock
    fetch('/productos/buscar')
        .then(res => res.json())
        .then(productos => {
            // Aviso de bajo stock de bultos
            const bajoStock = productos.filter(p => parseInt(p.cantidad_bultos) <= parseInt(p.bultos_min_aviso));
            const avisoDiv = document.getElementById('aviso-bajo-stock');
            if (bajoStock.length > 0) {
                avisoDiv.style.display = '';
                avisoDiv.innerHTML = '<b>¡Atención!</b> Los siguientes productos tienen bajo stock de bultos: ' +
                    bajoStock.map(p => `<span class='badge bg-danger mx-1'>${p.nombre}</span>`).join(' ');
            }
            // Top de productos con bajo stock por bulto
            const topBulto = productos.filter(p => parseInt(p.cantidad_por_bulto) <= parseInt(p.bultos_min_aviso));
            const tbody = document.querySelector('#tabla-bajo-bulto tbody');
            tbody.innerHTML = '';
            topBulto.forEach(p => {
                tbody.innerHTML +=
                    `<tr><td>${p.nombre}</td><td>${p.codigo}</td><td>${p.cantidad_por_bulto}</td><td>${p.bultos_min_aviso}</td></tr>`;
            });
        });
</script>
