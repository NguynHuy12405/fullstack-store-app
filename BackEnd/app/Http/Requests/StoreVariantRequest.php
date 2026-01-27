<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreVariantRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */

    protected $errorBag = 'variantAdd';

    public function authorize(): bool
    {
        return $this->user()->isAdmin();
    }

    public function rules(): array
    {
        return [
            'product_id' => 'required|exists:products,id',
            'color_id' => 'required|exists:colors,id',
            'size_id' => [
                'required',
                'exists:sizes,id',
                Rule::unique('variants')->where(function ($query) {
                    return $query->where('product_id', $this->product_id)->where('color_id', $this->color_id);
                })
            ],
            'price' => 'required|numeric|min:0',
            'sale_price' => 'nullable|numeric|min:0|lte:price',
            'quantity' => 'required|integer|min:0',
        ];
    }

    public function messages(): array
    {
        return [
            'color_id.required' => 'Vui lòng chọn màu sắc',
            'color_id.exists' => 'Màu sắc không tồn tại',
            'size_id.required' => 'Vui lòng chọn size',
            'size_id.exists' => 'Size không tồn tại',
            'size_id.unique' => 'Đã tồn tại!',
            'price.required' => 'Giá sản phẩm không được để trống',
            'price.numeric' => 'Giá sản phẩm phải là số',
            'price.min' => 'Giá sản phẩm phải lớn hơn hoặc bằng 0',
            'sale_price.numeric' => 'Giá khuyến mãi phải là số',
            'sale_price.min' => 'Giá khuyến mãi phải lớn hơn hoặc bằng 0',
            'sale_price.lte' => 'Giá khuyến mãi phải nhỏ hơn hoặc bằng giá gốc',
            'quantity.required' => 'Số lượng không được để trống',
            'quantity.integer' => 'Số lượng phải là số nguyên',
            'quantity.min' => 'Số lượng phải lớn hơn hoặc bằng 0',
        ];
    }
}
