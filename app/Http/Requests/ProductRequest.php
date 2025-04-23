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
            'precio_venta' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
        ];
    }
    public function attributes()
    {
        return [
            'precio_venta' => 'precio de venta',
            'nombre' => 'nombre del producto',
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
            'precio_venta.required' => 'El precio de venta es obligatorio.',
            'precio_venta.numeric' => 'El precio de venta debe ser un numero.',
            'precio_venta.min' => 'El precio de venta debe ser mayor que 0.',
            'stock.required' => 'El stock del producto es obligatorio.',
            'stock.integer' => 'El stock del producto debe ser un numero mayor o igual que 0.',
            'stock.min' => 'El stock del producto debe ser un numero mayor o igual que 0.',
        ];
    }
}
