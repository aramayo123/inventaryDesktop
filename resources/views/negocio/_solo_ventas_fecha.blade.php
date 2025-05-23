<div class="container my-4">
  <div class="card">
    <div class="card-header bg-secondary text-white">Solo ventas por fecha específica</div>
    <div class="card-body">
      <div class="row mb-3">
        <div class="col-md-4">
          <label for="fecha-solo-ventas" class="form-label">Selecciona una fecha:</label>
          <input type="date" id="fecha-solo-ventas" class="form-control">
        </div>
      </div>
      <div id="solo-ventas-fecha"></div>
      <div class="row">
        <div class="col-md-4">
          <div class="card text-center mb-2">
            <div class="card-body">
              <h6 class="card-subtitle mb-2 text-muted">Ventas totales</h6>
              <h4 class="card-title" id="ventas-totales-solo-fecha">$0.00</h4>
            </div>
          </div>
        </div>
        <div class="col-md-4">
          <div class="card text-center mb-2">
            <div class="card-body">
              <h6 class="card-subtitle mb-2 text-muted">Ganancia total</h6>
              <h4 class="card-title" id="ganancia-total-solo-fecha">$0.00</h4>
            </div>
          </div>
        </div>
        <div class="col-md-4">
          <div class="card text-center mb-2">
            <div class="card-body">
              <h6 class="card-subtitle mb-2 text-muted">Productos vendidos</h6>
              <h4 class="card-title" id="productos-vendidos-solo-fecha">0</h4>
            </div>
          </div>
        </div>
      </div>
      <script>
      function cargarSoloVentasPorFecha(fecha) {
        if (!fecha) return;
        fetch(`/facturas/resumen-por-fecha/${fecha}`)
          .then(res => res.json())
          .then(data => {
            document.getElementById('ventas-totales-solo-fecha').innerText = data.ventas_totales.toLocaleString('es-AR', { style: 'currency', currency: 'ARS' }).replace('¤', '').replace(',', '.');
            document.getElementById('ganancia-total-solo-fecha').innerText = data.ganancia_total.toLocaleString('es-AR', { style: 'currency', currency: 'ARS' }).replace('¤', '').replace(',', '.');
            document.getElementById('productos-vendidos-solo-fecha').innerText = data.productos_vendidos;
          });
      }
      document.getElementById('fecha-solo-ventas').addEventListener('change', function() {
        cargarSoloVentasPorFecha(this.value);
      });
      </script>
    </div>
  </div>
</div> 