<div class="container my-4">
  <div class="card">
    <div class="card-header bg-primary text-white">Resumen de ventas y ganancias (últimos X días)</div>
    <div class="card-body">
      <div class="row mb-3">
        <div class="col-md-4">
          <label for="dias-resumen" class="form-label">Selecciona rango de días:</label>
          <select id="dias-resumen" class="form-select">
            @for ($i = 1; $i <= 30; $i++)
              <option value="{{ $i }}">Últimos {{ $i }} día{{ $i > 1 ? 's' : '' }}</option>
            @endfor
          </select>
        </div>
      </div>
      <div class="row">
        <div class="col-md-4">
          <div class="card text-center mb-2">
            <div class="card-body">
              <h6 class="card-subtitle mb-2 text-muted">Ventas totales</h6>
              <h4 class="card-title" id="ventas-totales-resumen">$0.00</h4>
            </div>
          </div>
        </div>
        <div class="col-md-4">
          <div class="card text-center mb-2">
            <div class="card-body">
              <h6 class="card-subtitle mb-2 text-muted">Ganancia total</h6>
              <h4 class="card-title" id="ganancia-total-resumen">$0.00</h4>
            </div>
          </div>
        </div>
        <div class="col-md-4">
          <div class="card text-center mb-2">
            <div class="card-body">
              <h6 class="card-subtitle mb-2 text-muted">Productos vendidos</h6>
              <h4 class="card-title" id="productos-vendidos-resumen">0</h4>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<script>
function cargarResumenVentas(dias) {
  fetch(`/facturas/resumen-por-dias/${dias}`)
    .then(res => res.json())
    .then(data => {
      document.getElementById('ventas-totales-resumen').innerText = data.ventas_totales.toLocaleString('es-AR', { style: 'currency', currency: 'ARS' }).replace('¤', '').replace(',', '.');
      document.getElementById('ganancia-total-resumen').innerText = data.ganancia_total.toLocaleString('es-AR', { style: 'currency', currency: 'ARS' }).replace('¤', '').replace(',', '.');
      document.getElementById('productos-vendidos-resumen').innerText = data.productos_vendidos;
    });
}
document.addEventListener('DOMContentLoaded', function() {
  const select = document.getElementById('dias-resumen');
  cargarResumenVentas(select.value);
  select.addEventListener('change', function() {
    cargarResumenVentas(this.value);
  });
});
</script> 