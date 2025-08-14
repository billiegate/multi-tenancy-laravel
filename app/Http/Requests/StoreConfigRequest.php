<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreConfigRequest extends FormRequest
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
            'key'           => 'required|string|max:255|unique:configs,key',
            'value'         => 'required|string',
            'type'          => 'required|string|in:string,integer,boolean,json',
            'description'   => 'nullable|string|max:255',
            'is_active'     => 'boolean',
            'is_encrypted'  => 'boolean',
            'created_by'    => 'nullable|exists:users,id',
            'updated_by'    => 'nullable|exists:users,id',
            'deleted_by'    => 'nullable|exists:users,id'
        ];
    }
}
