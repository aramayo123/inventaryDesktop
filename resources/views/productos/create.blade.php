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
                                    <label for="codigo" class="form-label">Codigo de barras del producto: </label>
                                    <input type="text" name="codigo" class="form-control @error('codigo') is-invalid @enderror"
                                        value="{{ old('codigo') }}">
                                    @error('codigo')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="nombre" class="form-label">Nombre del producto: </label>
                                    <input type="text" name="nombre" class="form-control @error('nombre') is-invalid @enderror"
                                        value="{{ old('nombre') }}">
                                    @error('nombre')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="stock" class="form-label">Stock del producto: </label>
                                    <input type="number" name="stock" class="form-control @error('stock') is-invalid @enderror"
                                        value="{{ old('stock') }}">
                                    @error('stock')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="stock_minimo" class="form-label">Stock minimo del producto: </label>
                                    <input type="number" name="stock_minimo"
                                        class="form-control @error('stock_minimo') is-invalid @enderror"
                                        value="{{ old('stock_minimo') }}">
                                    @error('stock_minimo')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="precio_compra" class="form-label">Precio de compra del producto: </label>
                                    <input type="number" name="precio_compra"
                                        class="form-control @error('precio_compra') is-invalid @enderror"
                                        value="{{ old('precio_compra') }}">
                                    @error('precio_compra')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="precio_venta" class="form-label">Precio de venta del producto: </label>
                                    <input type="number" name="precio_venta"
                                        class="form-control @error('precio_venta') is-invalid @enderror"
                                        value="{{ old('precio_venta') }}">
                                    @error('precio_venta')
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
