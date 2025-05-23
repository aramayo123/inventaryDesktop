<div class="container my-4">
  <div class="card">
    <div class="card-header bg-info text-white">Ventas y ganancias por fecha específica</div>
    <div class="card-body">
      <div class="row mb-3">
        <div class="col-md-4">
          <label for="fecha-ventas" class="form-label">Selecciona una fecha:</label>
          <input type="date" id="fecha-ventas" class="form-control">
        </div>
      </div>
      <div id="resumen-ventas-fecha">
        <div class="row">
          <div class="col-md-4">
            <div class="card text-center mb-2">
              <div class="card-body">
                <h6 class="card-subtitle mb-2 text-muted">Ventas totales</h6>
                <h4 class="card-title" id="ventas-totales-fecha">$0.00</h4>
              </div>
            </div>
          </div>
          <div class="col-md-4">
            <div class="card text-center mb-2">
              <div class="card-body">
                <h6 class="card-subtitle mb-2 text-muted">Ganancia total</h6>
                <h4 class="card-title" id="ganancia-total-fecha">$0.00</h4>
              </div>
            </div>
          </div>
          <div class="col-md-4">
            <div class="card text-center mb-2">
              <div class="card-body">
                <h6 class="card-subtitle mb-2 text-muted">Productos vendidos</h6>
                <h4 class="card-title" id="productos-vendidos-fecha">0</h4>
              </div>
            </div>
          </div>
        </div>
      </div>
      <script>
      function cargarResumenPorFecha(fecha) {
        if (!fecha) return;
        fetch(`/facturas/resumen-por-fecha/${fecha}`)
          .then(res => res.json())
          .then(data => {
            document.getElementById('ventas-totales-fecha').innerText = data.ventas_totales.toLocaleString('es-AR', { style: 'currency', currency: 'ARS' }).replace('¤', '').replace(',', '.');
            document.getElementById('ganancia-total-fecha').innerText = data.ganancia_total.toLocaleString('es-AR', { style: 'currency', currency: 'ARS' }).replace('¤', '').replace(',', '.');
            document.getElementById('productos-vendidos-fecha').innerText = data.productos_vendidos;
          });
      }
      document.getElementById('fecha-ventas').addEventListener('change', function() {
        cargarResumenPorFecha(this.value);
      });
      </script>
    </div>
  </div>
</div> 