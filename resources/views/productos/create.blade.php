@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md">
                <div class="card">
                    <div class="card-body">
                        <div class="container">
                            <h2>Crear producto</h2>
                            <form action="{{ route('productos.store') }}" method="POST">
                                @csrf

                                <div class="mb-3">
                                    <label for="codigo" class="form-label">Codigo de barra: </label>
                                    <input type="text" name="codigo" class="form-control @error('codigo') is-invalid @enderror"
                                        value="{{ old('codigo') }}">
                                    @error('codigo')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="nombre" class="form-label">Nombre: </label>
                                    <input type="text" name="nombre" class="form-control @error('nombre') is-invalid @enderror"
                                        value="{{ old('nombre') }}">
                                    @error('nombre')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="row mb-3">
                                    <div class="col">
                                        <label class="form-label" for="cantidad_unidades">Cantidad de unidades: </label>
                                        <input type="number" name="cantidad_unidades" class="form-control @error('cantidad_unidades') is-invalid @enderror"
                                        value="{{ old('cantidad_unidades') }}">
                                        @error('cantidad_unidades')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                
                                <div class="row mb-3">
                                    <div class="col">
                                        <label class="form-label" for="cantidad_bultos">Cantidad de bultos: </label>
                                        <input type="number" name="cantidad_bultos" class="form-control @error('cantidad_bultos') is-invalid @enderror"
                                        value="{{ old('cantidad_bultos') }}">
                                        @error('cantidad_bultos')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="col">
                                        <label class="form-label" for="bultos_min_aviso">Bultos min para aviso: </label>
                                        <input type="number" name="bultos_min_aviso" class="form-control @error('bultos_min_aviso') is-invalid @enderror"
                                        value="{{ old('bultos_min_aviso') }}">
                                        @error('bultos_min_aviso')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label for="cantidad_por_bulto" class="form-label">Cantidad por bulto: </label>
                                    <input type="number" name="cantidad_por_bulto"
                                        class="form-control @error('cantidad_por_bulto') is-invalid @enderror"
                                        value="{{ old('cantidad_por_bulto') }}">
                                    @error('cantidad_por_bulto')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="precio_compra_unitario" class="form-label">Precio de compra por unidad: </label>
                                    <input type="number" step="0.01" name="precio_compra_unitario"
                                        class="form-control @error('precio_compra_unitario') is-invalid @enderror"
                                        value="{{ old('precio_compra_unitario') }}">
                                    @error('precio_compra_unitario')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="precio_compra_bulto" class="form-label">Precio de compra por bulto: </label>
                                    <input type="number" step="0.01" name="precio_compra_bulto"
                                        class="form-control @error('precio_compra_bulto') is-invalid @enderror"
                                        value="{{ old('precio_compra_bulto') }}">
                                    @error('precio_compra_bulto')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="precio_venta_unitario" class="form-label">Precio de venta: </label>
                                    <input type="number" step="0.01" name="precio_venta_unitario"
                                        class="form-control @error('precio_venta_unitario') is-invalid @enderror"
                                        value="{{ old('precio_venta_unitario') }}">
                                    @error('precio_venta_unitario')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <button type="submit" class="btn btn-success">Guardar</button>
                                <a href="{{ route('home') }}" class="btn btn-secondary">Volver</a>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
