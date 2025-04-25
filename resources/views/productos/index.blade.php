<?php
    function separarClaveMensaje(string $texto, string $separador = '__'): array
    {
        $partes = explode($separador, $texto, 2);

        return [
            'key' => $partes[0] ?? '',
            'mensaje' => $partes[1] ?? '',
        ];
    }
    if(session('success_product')){
        $respuesta = separarClaveMensaje(session('success_product'));
        $color = $respuesta['key'] == "CREATED" ? "success" : ($respuesta['key'] == "DESTROY" ? "warning":"info");
        $message = $respuesta['mensaje'];
    }
?>

<div class="container">
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
                    <th>Stock Min.</th>
                    <th>P / C.</th>
                    <th>P / V.</th>
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
                                <span>{{ $producto->stock }}</span>
                                <span onclick="editarCampo('stock', {{ $producto->id }})"
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
                        <td class="campo" data-field="stock_minimo" data-id="{{ $producto->id }}">
                            <div class="d-flex justify-content-between align-items-center">
                                <span>{{ $producto->stock_minimo }}</span>
                                <span onclick="editarCampo('stock_minimo', {{ $producto->id }})"
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
                        <td class="campo" data-field="precio_compra" data-id="{{ $producto->id }}">
                            <div class="d-flex justify-content-between align-items-center">
                                <span>${{ number_format($producto->precio_compra, 2) }}</span>
                                <span onclick="editarCampo('precio_compra', {{ $producto->id }})"
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
                        <td class="campo" data-field="precio_venta" data-id="{{ $producto->id }}">
                            <div class="d-flex justify-content-between align-items-center">
                                <span>${{ number_format($producto->precio_venta, 2) }}</span>
                                <span onclick="editarCampo('precio_venta', {{ $producto->id }})"
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
                            <a href="{{ route('productos.edit', $producto->id) }}"
                                class="btn btn-sm btn-warning">Editar</a>
                            !-->
                            <form action="{{ route('productos.destroy', $producto->id) }}" method="POST"
                                class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger"
                                    onclick="return confirm('¿Estás seguro?')">Eliminar</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>

<div id="paginacion" class="d-flex justify-content-center mt-4">
    asdasd
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<!-- Modal para editar un campo -->
<div class="modal fade" id="modal-editar-campo" tabindex="-1" aria-labelledby="modal-editar-campoLabel"
    aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal-editar-campoLabel">Editar Campo</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
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
    const perPage = 10; // <--- ¡esto es lo que te está faltando!
    function separarPorGuionBajo(texto) {
        if (texto !== 'precio_compra' && texto !== 'precio_venta' && texto !== 'stock_minimo')
            return texto + " del producto";

        return texto.replace(/_/g, ' de ');
    }

    function editarCampo(campo, id) {
        const Title = document.querySelector('#modal-editar-campoLabel');
        const text = document.querySelector('#modal-editar-labeltext');
        // Obtener el valor actual del campo
        const valorActual = document.querySelector(`#producto-${id} td[data-field="${campo}"]`).innerText.trim();

        // Asignar el valor actual al input del modal
        const inputCampoEditar = document.getElementById('campo-editar');

        // Establecer el tipo de input según el campo
        if (campo === 'precio_venta' || campo === 'precio_compra') {
            inputCampoEditar.type = 'number'; // Campo numérico para precios
            inputCampoEditar.step = '0.01'; // Permitir decimales
            inputCampoEditar.placeholder = 'Ingrese un valor numérico';
        } else if (campo === 'fecha') {
            inputCampoEditar.type = 'date'; // Campo de fecha
            inputCampoEditar.placeholder = 'Seleccione una fecha';
        } else if (campo === 'descripcion') {
            inputCampoEditar.type = 'text'; // Campo de texto (por defecto)
            inputCampoEditar.placeholder = 'Ingrese una descripción';
        } else {
            inputCampoEditar.type = 'text'; // Campo de texto por defecto
            inputCampoEditar.placeholder = 'Ingrese un valor';
        }
        Title.innerHTML = 'Editar ' + separarPorGuionBajo(campo);
        text.innerHTML = 'Ingrese el nuevo valor para ' + separarPorGuionBajo(campo);
        // Establecer el valor del input en base al valor actual
        inputCampoEditar.value = valorActual;

        // Mostrar el modal
        const modal = new bootstrap.Modal(document.getElementById('modal-editar-campo'));
        modal.show();

        // Guardar el campo y su ID para realizar la actualización
        document.getElementById('form-editar-campo').onsubmit = function(e) {
            e.preventDefault();
            guardarCampo(campo, id);
        };
    }

    function formatearNumero(valor) {
        const numero = parseFloat(valor);
        if (isNaN(numero)) return '0,00';

        return numero.toLocaleString('es-AR', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        });
    }

    function guardarCampo(campo, id) {
        const nuevoValor = document.getElementById('campo-editar').value;
        // Realizar la actualización en el backend  
        fetch(`/productos/${id}/actualizar-campo`, {
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
                    const campoElemento = document.querySelector(`#producto-${id} td[data-field="${campo}"]`);

                    const DivIcon = document.createElement('div');
                    DivIcon.classList.add('d-flex', 'justify-content-between', 'align-items-center');
                    const textoSpan = document.createElement('span');
                    if (campo === 'precio_compra' || campo === 'precio_venta') {
                        // Si es un campo numérico, convertirlo y actualizar
                        textoSpan.innerText = '$' + formatearNumero(nuevoValor);
                    } else {
                        // Para otros campos, puedes hacer una conversión personalizada si es necesario
                        textoSpan.innerText = nuevoValor;
                    }

                    // Agregar ícono de edición
                    const editarIcono = document.createElement('span');
                    editarIcono.setAttribute('onclick', `editarCampo('${campo}', ${id})`);
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


                    //document.querySelector(`#producto-${id} td[data-field="${campo}"]`).innerText = nuevoValor;

                    // Cerrar el modal
                    const modal = bootstrap.Modal.getInstance(document.getElementById('modal-editar-campo'));
                    modal.hide();
                } else {
                    alert('Error al guardar el cambio.');
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
                            <span>${producto.stock}</span>
                            <span onclick="editarCampo('stock', ${producto.id})"
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
                    <td class="campo" data-field="stock_minimo" data-id="${producto.id}">
                        <div class="d-flex justify-content-between align-items-center">
                            <span>${producto.stock_minimo}</span>
                            <span onclick="editarCampo('stock_minimo', ${producto.id})"
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
                    <td class="campo" data-field="precio_compra" data-id="${producto.id}">
                        <div class="d-flex justify-content-between align-items-center">
                            <span>${formatearNumero(producto.precio_compra, 2)}</span>
                            <span onclick="editarCampo('precio_compra', ${producto.id})"
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
                    <td class="campo" data-field="precio_venta" data-id="${producto.id}">
                        <div class="d-flex justify-content-between align-items-center">
                            <span>${ formatearNumero(producto.precio_venta, 2)}</span>
                            <span onclick="editarCampo('precio_venta', ${producto.id})"
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
                            class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger"
                                onclick="return confirm('¿Estás seguro?')">Eliminar</button>
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
</script>
