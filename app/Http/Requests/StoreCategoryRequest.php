<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;

class StoreCategoryRequest extends FormRequest
{
    protected $errorBag = 'add';

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
        return [
            'name' => 'required|string|max:255',
            'slug' => 'required|unique:categories,slug',
            'status' => 'nullable|boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Tên danh mục không được để trống',
            'slug.unique' => 'Tên danh mục đã tồn tại',
        ];
    }
}
