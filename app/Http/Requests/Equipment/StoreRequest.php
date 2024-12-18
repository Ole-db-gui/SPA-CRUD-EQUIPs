<?php

namespace App\Http\Requests\Equipment;

use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
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
            'equipment' => 'required|array',
            'equipment.*.equipment_type_id' => 'required|exists:equipment_types,id',
            'equipment.*.serial_number' => 'required|string|max:255',
            'equipment.*.description' => 'nullable|string',
        ];
    }
}
