<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ApiRequest extends FormRequest
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
            'api_name' => ['required', 'string', 'max:255'],
            'columns' => ['required', 'array', 'min:1'], // Deve ser uma lista de colunas
            'columns.*.name' => ['required', 'string', 'max:255'],
            'columns.*.type' => ['required', 'string', 'in:string,integer,boolean,date'], // Ajuste os tipos permitidos
        ];
    }

    public function messages(): array
    {
        return [
            'api_name.required' => 'O nome da API é obrigatório.',
            'api_name.string' => 'O nome da API deve ser um texto.',
            'columns.required' => 'É necessário informar pelo menos uma coluna.',
            'columns.array' => 'As colunas devem ser uma lista.',
            'columns.*.name.required' => 'O nome da coluna é obrigatório.',
            'columns.*.name.string' => 'O nome da coluna deve ser um texto.',
            'columns.*.type.required' => 'O tipo da coluna é obrigatório.',
            'columns.*.type.in' => 'O tipo da coluna deve ser string, integer, boolean ou date.',
        ];
    }
}
