<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Models\Product;

class ProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'codigo' => [
                'required',
                'string',
                Rule::unique('products', 'codigo')->ignore($this->route('producto')),
            ],
            'nombre' => 'required|string',
            'cantidad_unidades' => 'integer|min:0',
            'cantidad_bultos' => 'integer|min:0',
            'bultos_min_aviso' => 'integer|min:0',
            'cantidad_por_bulto' => 'required|integer|min:0',
            'precio_compra_unitario' => 'required|integer|min:0',
            'precio_compra_bulto' => 'required|integer|min:0',
            'precio_venta_unitario' => 'required|integer|min:0',
        ];
    }
    public function attributes()
    {
        return [
            'precio_venta' => 'precio de venta',
            'precio_compra' => 'precio de compra',
            'nombre' => 'nombre del producto',
            'cantidad_unidades' => 'cantidad de unidades',
            'cantidad_bultos' => 'cantidad de bultos',
            'bultos_min_aviso' => 'bultos min para aviso',
            'cantidad_por_bulto' => 'cantidad por bulto',
            'precio_compra_unitario' => 'precio de compra unitario',
            'precio_compra_bulto' => 'precio de compra por bulto',
            'precio_venta_unitario' => 'precio de venta unitario',
        ];
    }
    public function messages()
    {
        return [
            'codigo.required' => 'El codigo es obligatorio.',
            'codigo.string' => 'El codigo no debe contener numeros o caracteres especiales.',
            'codigo.unique' => 'El codigo ingresado ya existe y pertenece a otro producto.',
            'nombre.required' => 'El nombre del producto es obligatorio.',
            'nombre.string' => 'El nombre del producto no debe contener numeros o caracteres especiales.',
            'precio_venta_unitario.required' => 'El precio unitario de venta es obligatorio.',
            'precio_venta_unitario.numeric' => 'El precio unitario de venta debe ser un numero.',
            'precio_venta_unitario.min' => 'El precio unitario de venta debe ser mayor que 0.',
        ];
    }
}
