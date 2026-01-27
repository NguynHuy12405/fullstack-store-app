<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;

class StoreBrandRequest extends FormRequest
{
    protected $errorBag = 'add';

    public function authorize(): bool
    {
        return $this->user()->isAdmin();
    }
    //Tạo slug trước khi kiểm tra
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
            'slug' => 'required|unique:brands,slug',
            'image' => 'nullable|shop_image',
            'status' => 'nullable|boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Tên thương hiệu không được để trống',
            'slug.unique' => 'Tên thương hiệu đã tồn tại',
        ];
    }
}
