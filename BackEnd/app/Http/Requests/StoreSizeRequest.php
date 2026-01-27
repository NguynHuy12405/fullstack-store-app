<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreSizeRequest extends FormRequest
{
    protected $errorBag = 'add';

    public function authorize(): bool
    {
        return $this->user()->isAdmin();
    }

    public function rules(): array
    {
        return [
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('sizes')->where(function ($query) {
                    return $query->where('category_id', $this->category_id);
                })
            ],
            'category_id' => 'required|exists:categories,id',
            'status' => 'nullable|boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'name.unique' => 'Kích cỡ này đã tồn tại!',
            'name.required' => 'Tên kích cỡ không được để trống',
            'category_id.required' => 'Danh mục không được để trống',
            'category_id.exists' => 'Danh mục không tồn tại',
        ];
    }
}
