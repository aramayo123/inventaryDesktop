<style>
    input[type="number"] {
        -moz-appearance: textfield;
    }

    input[type="number"]::-webkit-inner-spin-button,
    input[type="number"]::-webkit-outer-spin-button {
        opacity: 1;
        /* Siempre visibles */
    }
</style>
<div class="">
    <!-- Información general -->
    <div class="row mb-4">
        <div class="col-md-4 mb-3">
            <div class="card text-center shadow">
                <div class="card-body">
                    <h6 class="card-subtitle mb-2 text-muted">Cantidad de productos</h6>
                    <h4 class="card-title" id="cantidadProductos">0</h4>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="card text-center shadow">
                <div class="card-body">
                    <h6 class="card-subtitle mb-2 text-muted">Total productos diferentes</h6>
                    <h4 class="card-title" id="productosDiferentes">0</h4>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="card text-center shadow">
                <div class="card-body">
                    <h6 class="card-subtitle mb-2 text-muted">Precio total</h6>
                    <h4 class="card-title" id="precioTotal">$0.00</h4>
                </div>
            </div>
        </div>
    </div>
    <!-- Buscar/agregar producto -->
    <div class="position-relative mb-4">
        <input id="product-search" type="text" class="form-control"
            placeholder="Escanear o ingresar código de producto..." autocomplete="off">

        <!-- Resultados position-absolute -->
        <div id="search-results" class="list-group  w-100 mt-1 d-none shadow">
            <!-- Los resultados se llenan dinámicamente -->
        </div>
    </div>

    <!-- Tabla del ticket -->
    <div class="table-responsive">
        <table class="table table-bordered table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th>Producto</th>
                    <th class="text-center">Cantidad</th>
                    <th class="text-center">Precio Unitario</th>
                    <th class="text-center">Subtotal</th>
                    <th class="text-center">Acciones</th>
                </tr>
            </thead>
            <tbody id="tablaProductos">
                <!-- Productos agregados aparecerán aquí -->
            </tbody>
        </table>
    </div>

    <!-- Botón finalizar venta -->
    <div class="d-flex justify-content-end mt-4">
        <button class="btn btn-success btn-lg" onclick="finalizarVenta()">Finalizar Venta</button>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    const products = @json($productos);

    let addedProducts = [];

    const input = document.getElementById('product-search');
    const resultsDiv = document.getElementById('search-results');
    const tablaProductos = document.getElementById('tablaProductos');

    function highlightMatch(text, query) {
        const words = query.split(/\s+/).filter(Boolean);
        if (words.length === 0) return text;

        const regex = new RegExp(`(${words.map(w => escapeRegExp(w)).join('|')})`, 'gi');
        return text.replace(regex, match => `<strong>${match}</strong>`);
    }

    function escapeRegExp(string) {
        return string.replace(/[.*+?^${}()|[\]\\]/g, '\\$&');
    }

    input.addEventListener('input', () => {
        const query = input.value.trim().toLowerCase();
        resultsDiv.innerHTML = '';
        resultsDiv.style.maxHeight = '300px';
        resultsDiv.style.overflowY = 'auto';

        if (query.length === 0) {
            resultsDiv.classList.add('d-none');
            return;
        }

        const words = query.split(/\s+/).filter(Boolean);

        const matches = products.filter(p => {
            const name = p.nombre.toLowerCase();
            const code = p.codigo.toLowerCase();
            return words.every(word =>
                name.includes(word) || code.includes(word)
            );
        });

        matches.forEach(product => {
            const nameHighlighted = highlightMatch(product.nombre, query);
            const codeHighlighted = highlightMatch(product.codigo, query);
            const alreadyAdded = addedProducts.includes(product);

            const resultItem = document.createElement('div');
            resultItem.className =
                "list-group-item list-group-item-action d-flex justify-content-between align-items-center";

            resultItem.innerHTML = `
            <div class="row align-items-center text-center w-100">
              <div class="col-5 text-muted">
                ${nameHighlighted}
              </div>
              <div class="col-3">
                <small class="text-muted">${codeHighlighted}</small>
              </div>
              <div class="col-2">
                <button onclick="AgregarCarrito(${product.id})" class="btn btn-sm ${ (!product.cantidad_unidades && !product.cantidad_bultos) ? 'btn-danger' : alreadyAdded ? 'btn-secondary' : 'btn-primary'} w-100" ${alreadyAdded || (!product.cantidad_unidades && !product.cantidad_bultos) ? 'disabled' : ''}>
                  ${ (!product.cantidad_unidades && !product.cantidad_bultos) ? 'Sin stock' : alreadyAdded ? 'Agregado' : 'Agregar'}
                </button>
              </div>
              <div class="col-1 text-center"">
                <input type="number" class="text-muted form-control form-control-sm" value="${product.cantidad_unidades + (product.cantidad_bultos * product.cantidad_por_bulto)}" disabled min="1" style="width:80px; margin:auto;">
              </div>
            </div>`;

            resultsDiv.appendChild(resultItem);
        });

        resultsDiv.classList.toggle('d-none', matches.length === 0);
    });

    function AgregarCarrito(productId) {
        const producto = products.find(p => p.id === productId);
        if (!producto) return;

        if (addedProducts.includes(producto)) {
            return; // Ya agregado
        }

        addedProducts.push(producto);
        //console.log(addedProducts);

        const row = document.createElement('tr');
        row.classList.add('fade-in');

        row.innerHTML = `
          <td>${producto.nombre}</td>
          <td class="text-center">
              <input type="number" class="form-control form-control-sm cantidad" name="cantidad" id="cantidad-${producto.id}" value="1" min="1" style="width:80px; margin:auto;">
          </td>
          <td class="text-center" id="precio-${producto.id}">$${parseFloat(producto.precio_venta_unitario).toFixed(2)}</td>
          <td class="text-center subtotal" id="subtotal-${producto.id}">$${parseFloat(producto.precio_venta_unitario).toFixed(2)}</td>
          <td class="text-center" data-product="${producto.id}">
              <button class="btn btn-danger btn-sm eliminar-btn">Eliminar</button>
          </td>
      `;

        tablaProductos.appendChild(row);

        actualizarTotales();

        // Reset input y resultados
        input.value = '';
        resultsDiv.classList.add('d-none');
    }

    // Escuchar clicks en "Eliminar"
    tablaProductos.addEventListener('click', function(e) {
        if (e.target.classList.contains('eliminar-btn')) {
            const row = e.target.closest('tr');
            // Animacion de salida
            row.classList.add('fade-out');

            setTimeout(() => {
                const idDelet = e.target.parentElement.dataset.product;
                // Eliminar del array
                addedProducts = addedProducts.filter(producto => {
                    if (producto.id == idDelet) {
                        return false; // No lo agregamos de nuevo
                    }
                    return true; // Lo mantenemos
                });
                //console.log(addedProducts);

                row.remove();
                actualizarTotales();
            }, 300); // Tiempo de la animación
        }
    });

    function actualizarSubtotal(idProducto) {
        const cantidadInput = document.getElementById(`cantidad-${idProducto}`);
        const precioText = document.getElementById(`precio-${idProducto}`).innerText.replace('$', '').replace(',', '');

        const cantidad = parseInt(cantidadInput.value) || 0;
        const precioUnitario = parseFloat(precioText) || 0;

        const nuevoSubtotal = cantidad * precioUnitario;

        const subtotalTd = document.getElementById(`subtotal-${idProducto}`);
        subtotalTd.innerText = `$${nuevoSubtotal.toFixed(2)}`;

        actualizarPrecioTotal(); // También actualizamos el total general
        actualizarTotales(); // Actualizamos los totales de la venta
        subtotalTd.classList.add('bg-warning', 'text-dark');
        setTimeout(() => {
            subtotalTd.classList.remove('bg-warning', 'text-dark');
        }, 300);
    }

    function actualizarPrecioTotal() {
        let total = 0;
        document.querySelectorAll('[id^="subtotal-"]').forEach(subtotalTd => {
            const subtotal = parseFloat(subtotalTd.innerText.replace('$', '').replace(',', '')) || 0;
            total += subtotal;
        });

        document.getElementById('precioTotal').innerText = `$${total.toFixed(2)}`;
    }
    // Delegar eventos
    document.addEventListener('input', function(e) {
        if (e.target.classList.contains('cantidad')) {
            const idProducto = e.target.id.split('-')[1];
            const product = addedProducts.find(p => p.id == idProducto);
            if (product) {
                const cantidad = parseInt(e.target.value) || 0;
                if (cantidad > product.cantidad_unidades + (product.cantidad_bultos * product.cantidad_por_bulto)) {
                    e.target.value = product.cantidad_unidades + (product.cantidad_bultos * product.cantidad_por_bulto);
                    Swal.fire({
                        position: "top-center",
                        icon: "error",
                        title: "No hay suficiente stock del producto " + product.nombre+".",
                        showConfirmButton: false,
                        timer: 1000
                    });
                    return;
                }
            }
            actualizarSubtotal(idProducto);
        }
    });
    document.addEventListener('change', function(e) {
        if (e.target.classList.contains('cantidad')) {
            const idProducto = e.target.id.split('-')[1];
            const product = addedProducts.find(p => p.id == idProducto);
            if (product) {
                const cantidad = parseInt(e.target.value) || 0;
                if (cantidad > product.cantidad_unidades + (product.cantidad_bultos * product.cantidad_por_bulto)) {
                    e.target.value = product.cantidad_unidades + (product.cantidad_bultos * product.cantidad_por_bulto);
                    Swal.fire({
                        position: "top-center",
                        icon: "error",
                        title: "No hay suficiente stock del producto " + product.nombre+".",
                        showConfirmButton: false,
                        timer: 1000
                    });
                    return;
                }
            }
            actualizarSubtotal(idProducto);
        }
    });
    // Actualizar totales de la venta
    function actualizarTotales() {
        let cantidadTotal = 0;
        let productosDiferentes = 0;
        let precioTotal = 0;

        document.querySelectorAll('#tablaProductos tr').forEach(tr => {
            const cantidadInput = tr.querySelector('.cantidad');
            const precioUnitario = parseFloat(tr.children[2].textContent.replace('$', '').replace(',', '')) ||
            0;

            const cantidad = parseInt(cantidadInput?.value || 0);
            const subtotalCell = tr.querySelector('.subtotal');

            if (cantidad > 0 && subtotalCell) {
                const subtotal = cantidad * precioUnitario;
                subtotalCell.textContent = `$${subtotal.toFixed(2)}`;

                cantidadTotal += cantidad;
                productosDiferentes++;
                precioTotal += subtotal;
            }
        });

        document.getElementById('cantidadProductos').textContent = cantidadTotal;
        document.getElementById('productosDiferentes').textContent = productosDiferentes;
        document.getElementById('precioTotal').textContent = `$${precioTotal.toFixed(2)}`;
    }


    function finalizarVenta() {
        if (addedProducts.length === 0) {
            Swal.fire({
                position: "top-center",
                icon: "error",
                title: "No hay productos en el carrito.",
                showConfirmButton: false,
                timer: 1000
            });
            return;
        }

        const cantidades = Array.from(document.querySelectorAll('.cantidad')).map(input => parseInt(input.value) || 0);
        const ProductosFinales = addedProducts.map((producto, index) => ({
            id: producto.id,
            cantidad: cantidades[index]
        }));

        fetch('/ventas/registrar-ventas', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ ProductosFinales })
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        position: "top-center",
                        icon: "success",
                        title: data.success,
                        showConfirmButton: false,
                        timer: 1000
                    });
                    location.reload();
                } else {
                    Swal.fire({
                        position: "top-center",
                        icon: "error",
                        title: data.error,
                        showConfirmButton: false,
                        timer: 1000
                    });
                }
            })
            .catch(error => console.error('Error:', error));
    }
</script>

<style>
    /* Animaciones */
    .fade-in {
        animation: fadeIn 0.4s ease forwards;
    }

    .fade-out {
        animation: fadeOut 0.3s ease forwards;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @keyframes fadeOut {
        from {
            opacity: 1;
            transform: translateY(0);
        }

        to {
            opacity: 0;
            transform: translateY(10px);
        }
    }
</style>
