<div class="container">
    <!-- Información general -->
    <div class="row mb-4">
        <div class="col-md-4 mb-3">
            <div class="card text-center shadow">
                <div class="card-body">
                    <h6 class="card-subtitle mb-2 text-muted">Ventas de hoy</h6>
                    <h4 class="card-title" id="ventasdehoy">$0.00</h4>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="card text-center shadow">
                <div class="card-body">
                    <h6 class="card-subtitle mb-2 text-muted">Ganancias de hoy</h6>
                    <h4 class="card-title" id="gananciadehoy">$0.00</h4>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="card text-center shadow">
                <div class="card-body">
                    <h6 class="card-subtitle mb-2 text-muted">Productos vendidos</h6>
                    <h4 class="card-title" id="productosvendidos">0</h4>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabla del ticket -->
    <div class="table-responsive">
        <table class="table table-bordered table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th>Cliente</th>
                    <th class="text-center">Cantidad de productos</th>
                    <th class="text-center">Cantidad de unidades</th>
                    <th class="text-center">Total</th>
                    <th class="text-center">Acciones</th>
                </tr>
            </thead>
            <tbody id="tablaVentas">

            </tbody>
        </table>
    </div>
</div>
<!-- Modal -->
<div class="modal fade" id="modal-show-campo" tabindex="-1" aria-labelledby="modal-show-campoLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg"> <!-- modal grande -->
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="modal-show-campoLabel">Ventas de la factura</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Cerrar"></button>
            </div>
            <div class="modal-body" id="modal_body_resumen">
                <!-- Aquí se inserta la tabla con JS -->
                <p class="text-muted text-center">Cargando ventas...</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>
<script>
    const facturas = @json($facturas);

    const tablaVentas = document.getElementById('tablaVentas');

    const ventasdehoy = document.getElementById('ventasdehoy');
    const gananciadehoy = document.getElementById('gananciadehoy');
    const productosvendidos = document.getElementById('productosvendidos');

    function ActualizarFacturas() {
        facturas.forEach(factura => {
            const row = document.createElement('tr');
            row.classList.add('fade-in');

            row.innerHTML = `
                <td>${factura.cliente}</td>
                <td class="text-center">${ factura.cantidad_productos }</td>
                <td class="text-center">${ factura.cantidad_unidades }</td>
                <td class="text-center">$${ parseFloat(factura.total_venta).toFixed(2) }</td>
                <td class="text-center" data-factura="${factura.id}">
                    <button class="btn btn-info btn-sm show-btn" onclick="mostrarVentasFactura(${factura.id})">Ver</button>
                    <button class="btn btn-danger btn-sm eliminar-btn">Eliminar</button>
                </td>
            `;
            tablaVentas.appendChild(row);
        });
    }
    // Escuchar clicks en "Eliminar"
    tablaVentas.addEventListener('click', function(e) {
        if (e.target.classList.contains('eliminar-btn')) {
            const row = e.target.closest('tr');
            // Animacion de salida
            row.classList.add('fade-out');
            row.remove();
            const idFactura = e.target.parentElement.dataset.factura;
            fetch('/facturas/eliminar-factura', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        factura_id: idFactura
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        ActualizarCarts();
                        alert(data.success);
                        /*
                        facturas = facturas.filter(factura => {
                            if (factura.id == idFactura) {
                                return false; // No lo agregamos de nuevo
                            }
                            return true; // Lo mantenemos
                        });
                        console.log(facturas);
                        */
                    } else {
                        console.log(data);
                        alert(data.error);
                    }
                })
                .catch(error => console.error('Error:', error));
            //actualizarTotales();
        } 
    });
    document.addEventListener('DOMContentLoaded', () => {
        ActualizarFacturas();
        ActualizarCarts();
    });

    function mostrarVentasFactura(facturaId) {
        fetch(`/facturas/todas-las-ventas/${facturaId}`)
            .then(response => response.json())
            .then(data => {
                const modalBody = document.getElementById('modal_body_resumen')
                //console.log(data);
                if (!data.length) {
                    modalBody.innerHTML =
                        '<div class="alert alert-info text-center">No hay ventas asociadas a esta factura.</div>';
                } else {
                    let totalUnidades = 0;
                    let totalVenta = 0;
                    let totalBultos = 0;

                    let html = `
                        <table class="table table-striped table-hover">
                            <thead class="table-dark">
                                <tr>
                                    <th>Producto</th>
                                    <th>Unidades</th>
                                    <th>Bultos</th>
                                    <th>Total vendido</th>
                                    <th>Fecha</th>
                                </tr>
                            </thead>
                        <tbody>`;

                    data.forEach(venta => {
                        totalUnidades += parseInt(venta.cantidad_unidades);
                        totalVenta += parseFloat(venta.total_venta);
                        totalBultos += (venta.cantidad_bultos);

                        html += `
                        <tr>
                            <td>${venta.producto_nombre}</td>
                            <td>${venta.cantidad_unidades}</td>
                            <td>${venta.cantidad_bultos}x${venta.cantidad_por_bulto}</td>
                            <td>$${parseFloat(venta.total_venta).toFixed(2)}</td>
                            <td>${formatFecha(venta.fecha)}</td>
                        </tr>`;
                    });

                    html += `
                    </tbody>
                    <tfoot class="table-light fw-bold">
                        <tr>
                            <td>Total</td>
                            <td>${totalUnidades}</td>
                            <td>${totalBultos}</td>
                            <td>$${totalVenta.toFixed(2)}</td>
                            <td></td>
                        </tr>
                    </tfoot>
                </table>`;

                    modalBody.innerHTML = html;
                }

                const modal = new bootstrap.Modal(document.getElementById('modal-show-campo'));
                modal.show();
            })
            .catch(error => {
                console.error('Error al obtener ventas:', error);
                document.getElementById('modal_body_resumen').innerHTML =
                    '<div class="alert alert-danger text-center">Hubo un error al cargar los datos.</div>';
            });
    }

    function formatFecha(fecha) {
        const date = new Date(fecha); // Convierte la fecha a un objeto Date
        const opciones = {
            weekday: 'long', // Día de la semana
            year: 'numeric', // Año
            month: 'long', // Mes completo
            day: 'numeric', // Día del mes
            hour: 'numeric', // Hora
            minute: 'numeric', // Minutos
            second: 'numeric', // Segundos
            hour12: true // Formato de 12 horas (si prefieres, puedes usar `false` para 24 horas)
        };

        // Devuelve la fecha formateada
        return new Intl.DateTimeFormat('es-ES', opciones).format(date);
    }

    function ActualizarCarts(){
        //ventasdehoy
        //gananciadehoy
        //productosvendidos
        fetch(`/facturas/facturas-hoy`)
            .then(response => response.json())
            .then(data => {
                console.log(data);
                if (!data.length) return;

                let ganancia = 0;
                let ventastotales = 0;
                for (let i = 0; i < data.length; i++) {
                    const venta = data[i];
                    ganancia += parseFloat(venta.ganancia);
                    ventastotales += parseFloat(venta.venta_total);
                }

                ventasdehoy.innerText = `$${ventastotales.toFixed(2)}`;
                gananciadehoy.innerText = `$${ganancia.toFixed(2)}`;
            })
            .catch(error => {
                console.error('Error al obtener datos', error);
            });
    }
</script>
