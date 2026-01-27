<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreShipStatusRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->isAdmin();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255|unique:ship_statuses,name',
        ];
    }
    public function messages(): array
    {
        return [
            'name.required' => 'Tên trạng thái không được để trống.',
            'name.unique'   => 'Tên trạng thái này đã tồn tại.',
        ];
    }
}
