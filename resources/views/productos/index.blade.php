<?php
function separarClaveMensaje(string $texto, string $separador = '__'): array
{
    $partes = explode($separador, $texto, 2);

    return [
        'key' => $partes[0] ?? '',
        'mensaje' => $partes[1] ?? '',
    ];
}
if (session('success_product')) {
    $respuesta = separarClaveMensaje(session('success_product'));
    $color = $respuesta['key'] == 'CREATED' ? 'success' : ($respuesta['key'] == 'DESTROY' ? 'warning' : 'info');
    $message = $respuesta['mensaje'];
}
?>

<div class="">
    @if (session('success_product'))
        <div class="alert alert-{{ $color }} alert-dismissible fade show" role="alert">
            <strong> {{ $message }}</strong>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="d-flex justify-content-between mb-3">
        <a href="{{ route('productos.create') }}" class="btn btn-primary">Crear producto</a>
        <a href="#" class="btn btn-secondary">Escanear</a>
    </div>
    <input type="text" id="search" placeholder="Buscar producto..." class="form-control mb-3">

    @if ($productos->isEmpty())
        <div class="alert alert-info">No hay productos registrados.</div>
    @else
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Codigo</th>
                    <th>Stock</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody id="productos-list">
                @foreach ($productos as $producto)
                    <tr id="producto-{{ $producto->id }}">
                        <td class="campo" data-field="nombre" data-id="{{ $producto->id }}">
                            <div class="d-flex justify-content-between align-items-center">
                                <span>{{ $producto->nombre }}</span>
                                <span onclick="editarCampo('nombre', {{ $producto->id }})"
                                    style="display: inline-flex; align-items: center; justify-content: center; padding: 4px; border: 1px solid #ccc; border-radius: 4px; cursor: pointer; transition: background-color 0.2s;"
                                    onmouseover="this.style.backgroundColor='#f0f0f0'"
                                    onmouseout="this.style.backgroundColor='transparent'">
                                    <svg width="15" height="15" fill="currentColor" class="bi bi-pencil-square"
                                        viewBox="0 0 16 16">
                                        <path
                                            d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z" />
                                        <path fill-rule="evenodd"
                                            d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z" />
                                    </svg>
                                </span>
                            </div>
                        </td>
                        <td class="campo" data-field="codigo" data-id="{{ $producto->id }}">
                            <div class="d-flex justify-content-between align-items-center">
                                <span>{{ $producto->codigo }}</span>
                                <span onclick="editarCampo('codigo', {{ $producto->id }})"
                                    style="display: inline-flex; align-items: center; justify-content: center; padding: 4px; border: 1px solid #ccc; border-radius: 4px; cursor: pointer; transition: background-color 0.2s;"
                                    onmouseover="this.style.backgroundColor='#f0f0f0'"
                                    onmouseout="this.style.backgroundColor='transparent'">
                                    <svg width="15" height="15" fill="currentColor" class="bi bi-pencil-square"
                                        viewBox="0 0 16 16">
                                        <path
                                            d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z" />
                                        <path fill-rule="evenodd"
                                            d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z" />
                                    </svg>
                                </span>
                            </div>
                        </td>
                        <td class="campo" data-field="stock" data-id="{{ $producto->id }}">
                            <div class="d-flex justify-content-between align-items-center">
                                <span>Stock</span>
                                <span onclick="editarCampo('stock', {{ $producto->id }}, @json($producto))"
                                    style="display: inline-flex; align-items: center; justify-content: center; padding: 4px; border: 1px solid #ccc; border-radius: 4px; cursor: pointer; transition: background-color 0.2s;"
                                    onmouseover="this.style.backgroundColor='#f0f0f0'"
                                    onmouseout="this.style.backgroundColor='transparent'">
                                    <svg width="15" height="15" fill="currentColor" class="bi bi-pencil-square"
                                        viewBox="0 0 16 16">
                                        <path
                                            d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z" />
                                        <path fill-rule="evenodd"
                                            d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z" />
                                    </svg>
                                </span>
                            </div>
                        </td>
                        <td>
                            <form action="{{ route('productos.destroy', $producto->id) }}" method="POST"
                                class="d-inline" id="form-eliminar-product">
                                @csrf
                                @method('DELETE')
                                <button type="button" class="btn btn-sm btn-danger btn-eliminar-producto"
                                onclick="confirmarEliminacion(this)">Eliminar</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>

<div id="paginacion" class="d-flex justify-content-center mt-4">

</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<!-- Modal para editar un campo -->
<div class="modal fade" id="modal-editar-campo" tabindex="-1" aria-labelledby="modal-editar-campoLabel"
    aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal-editar-campoLabel">Editar Campo</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="modal_body">
                <form id="form-editar-campo">
                    <div class="mb-3">
                        <label for="campo-editar" class="form-label" id="modal-editar-labeltext">Nuevo valor</label>
                        <input type="text" class="form-control" id="campo-editar" required>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="submit" form="form-editar-campo" class="btn btn-primary">Guardar cambios</button>
            </div>
        </div>
    </div>
</div>

<script>
    let productosFullList = [];
    let currentPage = 1;
    const perPage = 8; // 
    function editarCampo(campo, id, producto = null) {
        const Title = document.querySelector('#modal-editar-campoLabel');
        const ModalBody = document.querySelector('#modal_body');
        ModalBody.innerHTML = '';
       
        if (campo === 'stock') {
            Title.innerHTML = '¿En qué presentación viene el producto?';
            ModalBody.innerHTML += `
                <form id="form-editar-campo">
                    <div class="row mb-3">
                        <div class="col">
                            <label class="form-label" for="cantidad_unidades">Cantidad de unidades: </label>
                            <input type="number" class="form-control" name="cantidad_unidades" id="cantidad_unidades" value="${producto.cantidad_unidades}">
                        </div>
                        <div class="col">
                            <label class="form-label" for="cantidad_bultos">Cantidad de bultos: </label>
                            <input type="number" class="form-control" name="cantidad_bultos" id="cantidad_bultos" value="${producto.cantidad_bultos}">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col">
                            <label class="form-label" for="bultos_min_aviso">Bultos min para aviso: </label>
                            <input type="number" class="form-control" name="bultos_min_aviso" id="bultos_min_aviso" value="${producto.bultos_min_aviso}">
                        </div>
                        <div class="col">
                            <label class="form-label" for="cantidad_por_bulto">Cantidad por bulto: </label>
                            <input type="number" class="form-control" name="cantidad_por_bulto" id="cantidad_por_bulto" value="${producto.cantidad_por_bulto}">
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col">
                            <label class="form-label" for="precio_compra_unitario">Precio de compra por unidad: </label>
                            <input type="number" step="0.01" class="form-control" name="precio_compra_unitario" id="precio_compra_unitario" value="${producto.precio_compra_unitario}">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col">
                            <label class="form-label" for="precio_compra_bulto">Precio de compra por bulto: </label>
                            <input type="number" step="0.01" class="form-control" name="precio_compra_bulto" id="precio_compra_bulto" value="${producto.precio_compra_bulto}">
                        </div>
                    </div>
                    <div class="row mb-3">
                         <div class="col">
                            <label class="form-label" for="precio_venta_unitario">Precio de venta: </label>
                            <input type="number" step="0.01" class="form-control" name="precio_venta_unitario" id="precio_venta_unitario" value="${producto.precio_venta_unitario}">
                        </div>
                    </div>
                </form>
            `;
            // Mostrar el modal
            const modal = new bootstrap.Modal(document.getElementById('modal-editar-campo'));
            modal.show();
            // Guardar el campo y su ID para realizar la actualización
            document.getElementById('form-editar-campo').onsubmit = function(e) {
                e.preventDefault();
                const datos = {
                    cantidad_unidades: document.getElementById('cantidad_unidades') ? document.getElementById('cantidad_unidades').value : null,
                    cantidad_bultos: document.getElementById('cantidad_bultos') ? document.getElementById('cantidad_bultos').value : null,
                    bultos_min_aviso: document.getElementById('bultos_min_aviso') ? document.getElementById('bultos_min_aviso').value : null,
                    cantidad_por_bulto: document.getElementById('cantidad_por_bulto') ? document.getElementById('cantidad_por_bulto').value : null,
                    precio_compra_unitario: document.getElementById('precio_compra_unitario') ? document.getElementById('precio_compra_unitario').value : null,
                    precio_compra_bulto: document.getElementById('precio_compra_bulto') ? document.getElementById('precio_compra_bulto').value : null,
                    precio_venta_unitario: document.getElementById('precio_venta_unitario') ? document.getElementById('precio_venta_unitario').value : null,
                };
                //console.log(datos);
                guardarCampoSpecial(datos, id);
            };
            return;
        }
        ModalBody.innerHTML = `
            <form id="form-editar-campo">
                <div class="mb-3">
                    <label for="campo-editar" class="form-label" id="modal-editar-labeltext">Nuevo valor</label>
                    <input type="text" class="form-control" id="campo-editar" required>
                </div>
            </form>`;

        // Obtener el valor actual del campo
        const valorActual = document.querySelector(`#producto-${id} td[data-field="${campo}"]`).innerText.trim();

        const text = document.querySelector('#modal-editar-labeltext');
        // Asignar el valor actual al input del modal
        const inputCampoEditar = document.getElementById('campo-editar');
        // Establecer el tipo de input según el campo
        inputCampoEditar.type = 'text'; // Campo de texto por defecto
        inputCampoEditar.placeholder = 'Ingrese un valor';
        inputCampoEditar.value = valorActual;
        Title.innerHTML = 'Editar ' + (campo);
        text.innerHTML = 'Ingrese el nuevo valor para ' + (campo);
        // Establecer el valor del input en base al valor actual
       
        // Mostrar el modal
        const modal = new bootstrap.Modal(document.getElementById('modal-editar-campo'));
        modal.show();

        // Guardar el campo y su ID para realizar la actualización
        document.getElementById('form-editar-campo').onsubmit = function(e) {
            e.preventDefault();
            guardarCampo(campo, id);
        };
    }

    function guardarCampoSpecial(campos, idBuscado){
        //console.log(campos);
        fetch(`/productos/${idBuscado}/actualizar-campo`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    campo: 'stock',
                    cantidad_unidades: campos.cantidad_unidades ? campos.cantidad_unidades : null,
                    cantidad_bultos: campos.cantidad_bultos ? campos.cantidad_bultos : null,
                    bultos_min_aviso: campos.bultos_min_aviso ? campos.bultos_min_aviso : null,
                    cantidad_por_bulto: campos.cantidad_por_bulto ? campos.cantidad_por_bulto : null,
                    precio_compra_unitario: campos.precio_compra_unitario ? campos.precio_compra_unitario : null,
                    precio_compra_bulto: campos.precio_compra_bulto ? campos.precio_compra_bulto : null,
                    precio_venta_unitario: campos.precio_venta_unitario ? campos.precio_venta_unitario : null,
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Actualizar el valor en la tabla
                    const campoElemento = document.querySelector(`#producto-${idBuscado} td[data-field="stock"]`);

                    const DivIcon = document.createElement('div');
                    DivIcon.classList.add('d-flex', 'justify-content-between', 'align-items-center');
                    const textoSpan = document.createElement('span');
                    textoSpan.innerText = "Stock";

                    // Agregar ícono de edición
                    const editarIcono = document.createElement('span');
                    editarIcono.setAttribute('onclick', `editarCampo('stock', ${idBuscado}, JSON.parse('${JSON.stringify(data.producto).replace(/'/g, "\\'")}'))`);
                    editarIcono.style.cssText =
                        "display: inline-flex; align-items: center; justify-content: center; padding: 4px; border: 1px solid #ccc; border-radius: 4px; cursor: pointer; transition: background-color 0.2s;";
                    editarIcono.setAttribute('onmouseover', "this.style.backgroundColor='#f0f0f0'");
                    editarIcono.setAttribute('onmouseout', "this.style.backgroundColor='transparent'");

                    // Crear el SVG para el ícono de edición
                    editarIcono.innerHTML = `<svg width="15" height="15" fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
                                <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"/>
                                <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z"/>
                            </svg>`;

                    campoElemento.innerHTML = "";
                    // Añadir el ícono de edición al final de la celda o campo
                    DivIcon.appendChild(textoSpan);
                    DivIcon.appendChild(editarIcono);
                    campoElemento.appendChild(DivIcon);

                    // Cerrar el modal
                    const modal = bootstrap.Modal.getInstance(document.getElementById('modal-editar-campo'));
                    modal.hide();
                    console.log(data)
                    // para mejorar la logica vamos a actualizar el array en js tambien
                    const index = products.findIndex(p => p.id === idBuscado);
                    if (index !== -1) {
                        products[index].cantidad_unidades = data.producto.cantidad_unidades ? data.producto.cantidad_unidades : null;
                        products[index].cantidad_bultos = data.producto.cantidad_bultos ? data.producto.cantidad_bultos : null;
                        products[index].bultos_min_aviso = data.producto.bultos_min_aviso ? data.producto.bultos_min_aviso : null;
                        products[index].cantidad_por_bulto = data.producto.cantidad_por_bulto ? data.producto.cantidad_por_bulto : null;
                        products[index].precio_compra_unitario = data.producto.precio_compra_unitario ? data.producto.precio_compra_unitario : null;
                        products[index].precio_compra_bulto = data.producto.precio_compra_bulto ? data.producto.precio_compra_bulto : null;
                        products[index].precio_venta_unitario = data.producto.precio_venta_unitario ? data.producto.precio_venta_unitario : null;
                    }
                } else {
                    Swal.fire({
                        position: "top-center",
                        icon: "error",
                        title: 'Error al guardar el cambio.',
                        showConfirmButton: false,
                        timer: 1000
                    });
                }
            })
            .catch(error => {
                console.error('Error al actualizar el campo:', error);
            });
    }
    function guardarCampo(campo, idBuscado) {
        const nuevoValor = document.getElementById('campo-editar').value;
        // Realizar la actualización en el backend  
        fetch(`/productos/${idBuscado}/actualizar-campo`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    campo: campo,
                    valor: nuevoValor
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Actualizar el valor en la tabla
                    const campoElemento = document.querySelector(`#producto-${idBuscado} td[data-field="${campo}"]`);

                    const DivIcon = document.createElement('div');
                    DivIcon.classList.add('d-flex', 'justify-content-between', 'align-items-center');
                    const textoSpan = document.createElement('span');
                    textoSpan.innerText = nuevoValor;
                    // Agregar ícono de edición
                    const editarIcono = document.createElement('span');
                    editarIcono.setAttribute('onclick', `editarCampo('${campo}', ${idBuscado})`);
                    editarIcono.style.cssText =
                        "display: inline-flex; align-items: center; justify-content: center; padding: 4px; border: 1px solid #ccc; border-radius: 4px; cursor: pointer; transition: background-color 0.2s;";
                    editarIcono.setAttribute('onmouseover', "this.style.backgroundColor='#f0f0f0'");
                    editarIcono.setAttribute('onmouseout', "this.style.backgroundColor='transparent'");

                    // Crear el SVG para el ícono de edición
                    editarIcono.innerHTML = `<svg width="15" height="15" fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
                                <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"/>
                                <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z"/>
                            </svg>`;

                    campoElemento.innerHTML = "";
                    // Añadir el ícono de edición al final de la celda o campo
                    DivIcon.appendChild(textoSpan);
                    DivIcon.appendChild(editarIcono);
                    campoElemento.appendChild(DivIcon);
                    // Cerrar el modal
                    const modal = bootstrap.Modal.getInstance(document.getElementById('modal-editar-campo'));
                    modal.hide();

                    // para mejorar la logica vamos a actualizar el array en js tambien
                    const index = products.findIndex(p => p.id === idBuscado);
                    if (index !== -1) {
                        if(campo === 'nombre')
                            products[index].nombre = nuevoValor;
                        else if(campo === 'codigo')
                            products[index].codigo = nuevoValor;
                    }
                    //console.log(products)

                } else {
                    Swal.fire({
                        position: "top-center",
                        icon: "error",
                        title: 'Error al guardar el cambio.',
                        showConfirmButton: false,
                        timer: 1000
                    });
                }
            })
            .catch(error => {
                console.error('Error al actualizar el campo:', error);
            });
    }

    const tabla = document.getElementById('productos-list');
    const inputSearch = document.getElementById('search');
    let timeout = null;

    function renderProductos(data) {
        if (!tabla)
            return;

        tabla.innerHTML = '';

        if (data.length === 0) {
            tabla.innerHTML = `<tr><td colspan="7" class="text-center">No se encontraron productos.</td></tr>`;
            return;
        }

        data.forEach(producto => {
            tabla.innerHTML += `
                <tr id="producto-${producto.id}">
                    <td class="campo" data-field="nombre" data-id="${producto.id}">
                        <div class="d-flex justify-content-between align-items-center">
                            <span>${producto.nombre}</span>
                            <span onclick="editarCampo('nombre', ${producto.id})"
                                style="display: inline-flex; align-items: center; justify-content: center; padding: 4px; border: 1px solid #ccc; border-radius: 4px; cursor: pointer; transition: background-color 0.2s;"
                                onmouseover="this.style.backgroundColor='#f0f0f0'"
                                onmouseout="this.style.backgroundColor='transparent'">
                                <svg width="15" height="15" fill="currentColor" class="bi bi-pencil-square"
                                    viewBox="0 0 16 16">
                                    <path
                                        d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z" />
                                    <path fill-rule="evenodd"
                                        d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z" />
                                </svg>
                            </span>
                        </div>
                    </td>
                    <td class="campo" data-field="codigo" data-id="${producto.id}">
                        <div class="d-flex justify-content-between align-items-center">
                            <span>${producto.codigo}</span>
                            <span onclick="editarCampo('codigo', ${producto.id})"
                                style="display: inline-flex; align-items: center; justify-content: center; padding: 4px; border: 1px solid #ccc; border-radius: 4px; cursor: pointer; transition: background-color 0.2s;"
                                onmouseover="this.style.backgroundColor='#f0f0f0'"
                                onmouseout="this.style.backgroundColor='transparent'">
                                <svg width="15" height="15" fill="currentColor" class="bi bi-pencil-square"
                                    viewBox="0 0 16 16">
                                    <path
                                        d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z" />
                                    <path fill-rule="evenodd"
                                        d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z" />
                                </svg>
                            </span>
                        </div>
                    </td>
                    <td class="campo" data-field="stock" data-id="${producto.id}">
                        <div class="d-flex justify-content-between align-items-center">
                            <span>Stock</span>
                            <span onclick='editarCampo("stock", ${producto.id}, ${JSON.stringify(producto).replace(/"/g, '&quot;')})'
                                style="display: inline-flex; align-items: center; justify-content: center; padding: 4px; border: 1px solid #ccc; border-radius: 4px; cursor: pointer; transition: background-color 0.2s;"
                                onmouseover="this.style.backgroundColor='#f0f0f0'"
                                onmouseout="this.style.backgroundColor='transparent'">
                                <svg width="15" height="15" fill="currentColor" class="bi bi-pencil-square"
                                    viewBox="0 0 16 16">
                                    <path
                                        d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z" />
                                    <path fill-rule="evenodd"
                                        d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z" />
                                </svg>
                            </span>
                        </div>
                    </td>
                    <td>
                        <!--
                        <a href="/productos/${producto.id}/edit"
                            class="btn btn-sm btn-warning">Editar</a>
                        !-->
                        <form action="/productos/${producto.id}" method="POST"
                            class="d-inline" id="form-eliminar-product-${producto.id}">
                            @csrf
                            @method('DELETE')
                            <button type="button" class="btn btn-sm btn-danger btn-eliminar-producto"
                            onclick="confirmarEliminacion(this)">Eliminar</button>
                        </form>
                    </td>
                </tr>
            `;
        });
    }

    function buscarProductos(query = '', page = 1) {
        fetch(`/productos/buscar?search=${encodeURIComponent(query)}&page=${page}`)
            .then(res => res.json())
            .then(data => {
                productosFullList = data;
                currentPage = 1;

                const paginatedData = {
                    data: productosFullList.slice(0, perPage), // productos a mostrar
                    total: productosFullList.length,
                    per_page: perPage,
                    current_page: currentPage,
                    last_page: Math.ceil(productosFullList.length / perPage)
                };

                renderProductos(paginatedData.data);
                renderPaginacion(paginatedData);
            })
            .catch(err => console.error('Error al cargar productos:', err));
    }

    inputSearch.addEventListener('input', () => {
        clearTimeout(timeout);
        timeout = setTimeout(() => {
            buscarProductos(inputSearch.value, 1); // <-- importante: página 1
        }, 500);
    });
    document.addEventListener('DOMContentLoaded', () => {
        buscarProductos(inputSearch.value, 1); // Carga inicial
        
    });

    function renderPaginacion(data) {
        const paginacion = document.getElementById('paginacion');
        paginacion.innerHTML = '';

        const ul = document.createElement('ul');
        ul.className = 'pagination';

        for (let i = 1; i <= data.last_page; i++) {
            const li = document.createElement('li');
            li.className = 'page-item' + (i === data.current_page ? ' active' : '');

            const btn = document.createElement('button');
            btn.className = 'page-link';
            btn.textContent = i;
            btn.onclick = () => changePage(i); // llamás a tu función simulada

            li.appendChild(btn);
            ul.appendChild(li);
        }

        paginacion.appendChild(ul);
    }

    function changePage(page) {
        currentPage = page;

        const paginatedData = {
            data: productosFullList.slice((page - 1) * perPage, page * perPage),
            total: productosFullList.length,
            per_page: perPage,
            current_page: page,
            last_page: Math.ceil(productosFullList.length / perPage)
        };

        renderProductos(paginatedData.data);
        renderPaginacion(paginatedData);
    }

    function confirmarEliminacion(btn) {
        Swal.fire({
            title: '¿Estás seguro?',
            text: "¡Esta acción no se puede deshacer!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                btn.closest('form').submit();
            }
        });
    }
</script>
