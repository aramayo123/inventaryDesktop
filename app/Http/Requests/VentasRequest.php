<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class VentasRequest extends FormRequest
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
            'cliente' => 'required|string|max:255',
            'product_id' => 'required|exists:products,id',
            'cantidad_unidades' => 'required|integer|min:1',
            'cantidad_bultos' => 'required|integer|min:1',
            'total_venta' => 'required|numeric|min:0',
        ];
    }
}
