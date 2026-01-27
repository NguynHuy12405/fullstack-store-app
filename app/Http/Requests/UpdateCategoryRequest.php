<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;

class UpdateCategoryRequest extends FormRequest
{
    protected $errorBag = 'edit';

    public function authorize(): bool
    {
        return $this->user()->isAdmin();
    }
    protected function prepareForValidation()
    {
        $this->merge([
            'slug' => Str::slug($this->name),
        ]);
    }
    public function rules(): array
    {
        $id = $this->route('category')->id;

        return [
            'name'   => 'required|string|max:255|',
            'slug'   => 'required|string|max:255|unique:categories,slug,' . $id,
            'status' => 'required|boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Tên danh mục không được để trống',
            'slug.unique' => 'Tên danh mục đã tồn tại',
            'status.required' => 'Trạng thái không được để trống',
            'status.boolean' => 'Trạng thái không hợp lệ',
        ];
    }
}
