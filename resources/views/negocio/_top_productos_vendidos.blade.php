<div class="container my-4">
  <div class="card">
    <div class="card-header bg-success text-white">Top productos más vendidos (últimos X días)</div>
    <div class="card-body">
      <div class="row mb-3">
        <div class="col-md-4">
          <label for="dias-top" class="form-label">Selecciona rango de días:</label>
          <select id="dias-top" class="form-select">
            @for ($i = 1; $i <= 30; $i++)
              <option value="{{ $i }}">Últimos {{ $i }} día{{ $i > 1 ? 's' : '' }}</option>
            @endfor
          </select>
        </div>
      </div>
      <div class="table-responsive">
        <table class="table table-bordered table-hover mb-0" id="tabla-top-productos">
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
function cargarTopProductosVendidos(dias) {
  fetch(`/facturas/top-productos-vendidos/${dias}`)
    .then(res => res.json())
    .then(data => {
      const tbody = document.querySelector('#tabla-top-productos tbody');
      tbody.innerHTML = '';
      if (data.length === 0) {
        tbody.innerHTML = '<tr><td colspan="4" class="text-center">No hay ventas en este periodo.</td></tr>';
        return;
      }
      data.forEach(p => {
        tbody.innerHTML += `<tr><td>${p.nombre}</td><td>${p.codigo}</td><td>${p.cantidad_vendida}</td><td>${p.total_recaudado.toLocaleString('es-AR', { style: 'currency', currency: 'ARS' }).replace('¤', '').replace(',', '.')}</td></tr>`;
      });
    });
}
document.addEventListener('DOMContentLoaded', function() {
  const select = document.getElementById('dias-top');
  cargarTopProductosVendidos(select.value);
  select.addEventListener('change', function() {
    cargarTopProductosVendidos(this.value);
  });
});
</script> 