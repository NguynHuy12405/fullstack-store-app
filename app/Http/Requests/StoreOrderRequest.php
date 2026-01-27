<?php

namespace App\Http\Requests;

use App\Models\Variant;
use Illuminate\Foundation\Http\FormRequest;

class StoreOrderRequest extends FormRequest
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
            'name' => 'required|string|max:255',
            'phone' => 'required|regex:/^(03|05|07|08|09)+([0-9]{8})$/',
            'address' => 'required|string|max:255',
            'products' => 'required|array|min:1',
            'products.*.variant_id' => 'required|distinct|exists:variants,id',
            'products.*.quantity' => 'required|integer|min:1',
        ];
    }
    public function messages(): array
    {
        return [
            'name.required' => 'Tên không được để trống',
            'name.max' => 'Tên không được vượt quá 255 ký tự',
            'phone.required' => 'Số điện thoại không được để trống',
            'phone.regex' => 'Số điện thoại không hợp lệ',
            'address.required' => 'Địa chỉ không được để trống',
            'address.max' => 'Địa chỉ không được vượt quá 255 ký tự',
            'products.required' => 'Danh sách sản phẩm không được để trống',
            'products.array' => 'Danh sách sản phẩm phải là 1 mảng',
            'products.min' => 'Danh sách sản phẩm phải có ít nhất 1 sản phẩm',
            'products.*.variant_id.required' => 'Sản phẩm không được để trống',
            'products.*.variant_id.exists' => 'Sản phẩm không tồn tại',
            'products.*.variant_id.distinct' => 'Sản phẩm trùng, hãy cộng dồn',
            'products.*.quantity.required' => 'Số lượng không được để trống.',
            'products.*.quantity.integer' => 'Số lượng phải là số nguyên.',
            'products.*.quantity.min' => 'Số lượng sản phẩm tối thiểu phải là 1.',
        ];
    }
}
