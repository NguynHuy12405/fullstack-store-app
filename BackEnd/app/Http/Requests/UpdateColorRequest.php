<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateColorRequest extends FormRequest
{
    protected $errorBag = 'edit';

    public function authorize(): bool
    {
        return $this->user()->isAdmin();
    }

    public function rules(): array
    {
        $id = $this->route('color')->id;
        return [
            'name' => 'required|string|max:255|unique:colors,name,' . $id,
            'hex_code' => [
                'required',
                'regex:/^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/',
            ],
            'status' => 'required|boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Tên màu sắc không được để trống.',
            'name.unique' => 'Tên màu sắc này đã tồn tại.',
            'status.required' => 'Trạng thái không được để trống',
            'status.boolean' => 'Trạng thái không hợp lệ',
            'hex_code.required' => 'Mã màu không được để trống',
            'hex_code.regex' => 'Mã màu không hợp lệ',
        ];
    }
}
