<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreColorRequest extends FormRequest
{
    protected $errorBag = 'add';

    public function authorize(): bool
    {
        return $this->user()->isAdmin();
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255|unique:colors,name',
            'hex_code' => [
                'required',
                'regex:/^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/',
            ],
            'status' => 'nullable|boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Tên màu không được để trống',
            'name.unique' => 'Màu đã tồn tại',
            'hex_code.required' => 'Mã màu không được để trống',
            'hex_code.regex' => 'Mã màu không hợp lệ',
        ];
    }
}
