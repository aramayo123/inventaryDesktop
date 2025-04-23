<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Http\Requests\ProductRequest;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index()
    {
        //
        $productos = Product::all();
        return view('productos.index', compact('productos'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        return view('productos.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ProductRequest $request)
    {
        // todo validado
        $product = Product::where('codigo', $request->codigo)->first();

        if ($product) {
            $product->stock += $request->stock;
            $product->save();
            return redirect()->route('home')->with('success_product', 'Stock actualizado');
        } else {
            Product::create($request->all());
            return redirect()->route('home')->with('success_product', 'Producto creado');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
        $producto = Product::findOrFail($id);
        return view('productos.edit', compact('producto'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ProductRequest $request, string $id)
    {
        //
        $producto = Product::findOrFail($id);
        $producto->codigo = $request->codigo;
        $producto->nombre = $request->nombre;
        $producto->stock = $request->stock;
        $producto->stock_minimo = $request->stock_minimo;
        $producto->precio_compra = $request->precio_compra;
        $producto->precio_venta = $request->precio_venta;
        $producto->save();
        return redirect()->route('home')->with('success_product', 'Producto actualizado');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        Product::destroy($id);
        return redirect()->route('home')->with('success_product',  "El producto ha sido eliminado con exito!");
    }
}
