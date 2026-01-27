<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreImportRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->isAdmin();
    }
    protected function prepareForValidation()
    {
        $this->merge([
            'variants' => json_decode($this->items, true),
        ]);
    }
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'user_id' => 'required|exists:users,id',
            'variants' => 'required|array|min:1',
            'status' => 'nullable|boolean',
            'variants.*.variant_id' => 'required|distinct|exists:variants,id',
            'variants.*.quantity' => 'required|integer|min:1',
            'variants.*.price' => 'required|numeric|min:0',
        ];
    }
    public function messages(): array
    {
        return [
            'variants.required' => 'Danh sách sản phẩm không được để trống',
            'variants.array' => 'Danh sách sản phẩm phải là 1 mảng',
            'variants.min' => 'Danh sách sản phẩm phải có ít nhất 1 sản phẩm',
            'variants.*.variant_id.required' => 'Sản phẩm không được để trống',
            'variants.*.variant_id.exists' => 'Sản phẩm không tồn tại',
            'variants.*.quantity.required' => 'Số lượng không được để trống.',
            'variants.*.quantity.integer' => 'Số lượng phải là số nguyên.',
            'variants.*.quantity.min' => 'Số lượng sản phẩm tối thiểu phải là 1.',
            'variants.*.price.numeric' => 'Giá nhập phải là số',
            'variants.*.price.min' => 'Giá nhập phải lớn hơn hoặc bằng 0',
        ];
    }
}
