<div class="container my-4">
  <div class="card">
    <div class="card-header bg-warning text-dark">Top productos más vendidos por día</div>
    <div class="card-body">
      <div class="row mb-3">
        <div class="col-md-4">
          <label for="fecha-top-productos" class="form-label">Selecciona una fecha:</label>
          <input type="date" id="fecha-top-productos" class="form-control">
        </div>
      </div>
      <div class="table-responsive">
        <table class="table table-bordered table-hover mb-0" id="tabla-top-productos-fecha">
          <thead class="table-light">
            <tr>
              <th>Producto</th>
              <th>Código</th>
              <th>Cantidad vendida</th>
              <th>Total recaudado</th>
            </tr>
          </thead>
          <tbody></tbody>
        </table>
      </div>
    </div>
  </div>
</div>
<script>
function cargarTopProductosVendidosPorFecha(fecha) {
  if (!fecha) return;
  fetch(`/facturas/top-productos-vendidos-por-fecha/${fecha}`)
    .then(res => res.json())
    .then(data => {
      const tbody = document.querySelector('#tabla-top-productos-fecha tbody');
      tbody.innerHTML = '';
      if (data.length === 0) {
        tbody.innerHTML = '<tr><td colspan="4" class="text-center">No hay ventas en este día.</td></tr>';
        return;
      }
      data.forEach(p => {
        tbody.innerHTML += `<tr><td>${p.nombre}</td><td>${p.codigo}</td><td>${p.cantidad_vendida}</td><td>${p.total_recaudado.toLocaleString('es-AR', { style: 'currency', currency: 'ARS' }).replace('¤', '').replace(',', '.')}</td></tr>`;
      });
    });
}
document.getElementById('fecha-top-productos').addEventListener('change', function() {
  cargarTopProductosVendidosPorFecha(this.value);
});
</script> 