<div class="container">
  <h2 class="mb-4">Productos</h2>

  @if (session('success_product'))
    <div class="alert alert-success" role="alert">
        {{ session('success_product') }}
    </div>
  @endif

  <a href="{{ route('productos.create') }}" class="btn btn-primary mb-3">Crear producto</a>

  @if ($productos->isEmpty())
    <div class="alert alert-info">No hay productos registrados.</div>
  @else
    <table class="table table-bordered">
      <thead>
        <tr>
          <th>Codigo</th>
          <th>Nombre</th>
          <th>Stock</th>
          <th>Stock minimo</th>
          <th>Precio compra</th>
          <th>Precio venta</th>
          <th>Acciones</th>
        </tr>
      </thead>
      <tbody>
        @foreach ($productos as $producto)
          <tr>
            <td>{{ $producto->codigo }}</td>
            <td>{{ $producto->nombre }}</td>
            <td>{{ $producto->stock }}</td>
            <td>{{ $producto->stock_minimo }}</td>
            <td>{{ $producto->precio_compra }}</td>
            <td>{{ $producto->precio_venta }}</td>
            <td>
              <a href="{{ route('productos.edit', $producto->id) }}" class="btn btn-sm btn-warning">Editar</a>
              <form action="{{ route('productos.destroy', $producto->id) }}" method="POST" class="d-inline">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('¿Estás seguro?')">Eliminar</button>
              </form>
            </td>
          </tr>
        @endforeach
      </tbody>
    </table>
  @endif
</div>