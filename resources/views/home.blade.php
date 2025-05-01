<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
@extends('layouts.app')
<?php 
    use App\Models\Product;
    use App\Models\Factura;
    $productos = Product::all();
    $facturas = Factura::all();
?>
<style>
    .tab-content>.tab-pane {
        display: none;
        opacity: 0;
        transition: opacity 0.4s ease-in-out;
    }

    .tab-content>.tab-pane.show {
        display: block;
        opacity: 1;
    }

    .nav-tabs .nav-link.active {
        background-color: #f8f9fa;
        border-bottom: 3px solid #0d6efd;
        font-weight: bold;
    }
</style>
@section('content')
    <div class="px-4">
        <div class="row justify-content-center">
            <div class="col-md">
                <div class="card">
                    <div class="card-body">
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif
                        <!-- Navegación -->
                        <ul class="nav nav-tabs justify-content-center" id="tabs">
                            <li class="nav-item">
                                <button class="nav-link" data-tab="inventario">Inventario</button>
                            </li>
                            <li class="nav-item">
                                <button class="nav-link" data-tab="ventas">Ventas</button>
                            </li>
                            <li class="nav-item">
                                <button class="nav-link" data-tab="resumen">Resumen</button>
                            </li>
                            <li class="nav-item">
                                <button class="nav-link" data-tab="negocio">Sobre mi negocio</button>
                            </li>
                        </ul>
                        <div class="card-body">
                            <!-- Contenido -->
                            <div class="tab-content">
                                <div class="tab-pane" id="inventario">
                                    @include('productos.index')
                                </div>
                                <div class="tab-pane" id="ventas">
                                    @include('ventas.index')
                                </div>
                                <div class="tab-pane" id="resumen">
                                    @include('ventas.resumen')
                                </div>
                                <div class="tab-pane" id="negocio">
                                    @include('minegocio')
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        const buttons = document.querySelectorAll('[data-tab]');
        const panes = document.querySelectorAll('.tab-pane');
        const show_modal = @json(session('success_product') ? "inventario" : "");

        document.addEventListener('DOMContentLoaded', () => {
            let none = false;
            buttons.forEach(btn => btn.classList.remove('active'));
            panes.forEach(pane => pane.classList.remove('show'));
            buttons.forEach(button => {
                // Mostrar contenido correspondiente
                panes.forEach(pane => {
                    if (pane.id === button.dataset.tab && show_modal === pane.id) {
                        pane.classList.add('show');
                        button.classList.add('active');
                        none = true;
                    }
                });
            });
            if(!none){
                const button = document.querySelector('[data-tab="ventas"]');
                const pane = document.querySelector('#ventas');
                pane.classList.add('show');
                button.classList.add('active');
            }
        });

        buttons.forEach(button => {
            button.addEventListener('click', () => {
                // Cambiar pestaña activa
                buttons.forEach(btn => btn.classList.remove('active'));
                button.classList.add('active');

                // Mostrar contenido correspondiente
                panes.forEach(pane => {
                    if (pane.id === button.dataset.tab) {
                        pane.classList.add('show');
                    } else {
                        pane.classList.remove('show');
                    }
                });
            });
        });
    </script>
@endsection