<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;

class UpdateProductRequest extends FormRequest
{
    protected $errorBag = 'edit';

    public function authorize(): bool
    {
        return $this->user()->isAdmin();
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'variants' => json_decode($this->variants_data, true),
            'slug' => Str::slug($this->name),
        ]);
    }

    public function rules(): array
    {
        $id = $this->route('product')->id;

        return [
            'name' => 'required|string|max:255|',
            'slug' => 'required|string|max:255|unique:products,slug,' . $id,
            'description' => 'nullable|string|max:10000',
            'thumbnail' => 'nullable|shop_image',
            'status' => 'nullable|boolean',
            'category_id' => 'prohibited',
            'brand_id' => 'required|exists:brands,id',
            //Variant
            'variants' => [
                'required',
                'array',
                'min:1',
                'required',
                'array',
                function ($attribute, $value, $fail) {
                    //Kiểm tra trùng lặp
                    $duplicates = collect($value)->map(function ($item) {
                        return $item['color_id'] . '-' . $item['size_id'];
                    });

                    if ($duplicates->unique()->count() < $duplicates->count()) {
                        $fail('Trong danh sách biến thể có các dòng bị trùng lặp Màu và Size.');
                    }
                }
            ],
            'variants.*.color_id' => 'required|exists:colors,id',
            'variants.*.size_id' => 'required|exists:sizes,id',
            'variants.*.price' => 'required|numeric|min:0',
            'variants.*.sale_price' => 'nullable|numeric|min:0|lte:variants.*.price',
            'variants.*.quantity' => 'required|integer|min:0',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Tên sản phẩm không được để trống',
            'slug.unique' => 'Tên sản phẩm đã tồn tại',
            'description.max' => 'mô tả sản phẩm không quá 10000 ký tự',
            'status.boolean' => 'Trạng thái không hợp lệ',
            'category_id.prohibited' => 'Không được phép chỉnh sửa danh mục!',
            'brand_id.exists' => 'Thương hiệu không tồn tại',


            //Variant
            'variants.required' => 'Bạn phải tạo ít nhất một biến thể cho sản phẩm',
            'variants.array' => 'Dữ liệu biến thể không đúng định dạng',
            'variants.min' => 'Danh sách biến thể phải có ít nhất 1 biến thể',
            'variants.*.color_id.required' => 'Vui lòng chọn màu sắc ở dòng :position',
            'variants.*.color_id.exists' => 'Màu sắc ở dòng :position không tồn tại',
            'variants.*.size_id.required' => 'Vui lòng chọn size ở dòng :position',
            'variants.*.size_id.exists' => 'Size ở dòng :position không tồn tại',
            'variants.*.price.required' => 'Giá sản phẩm ở dòng :position không được để trống',
            'variants.*.price.numeric' => 'Giá sản phẩm ở dòng :position phải là số',
            'variants.*.price.min' => 'Giá sản phẩm ở dòng :position phải lớn hơn hoặc bằng 0',
            'variants.*.sale_price.numeric' => 'Giá khuyến mãi ở dòng :position phải là số',
            'variants.*.sale_price.min' => 'Giá khuyến mãi ở dòng :position phải lớn hơn hoặc bằng 0',
            'variants.*.sale_price.lte' => 'Giá khuyến mãi ở dòng :position phải nhỏ hơn hoặc bằng giá gốc',
            'variants.*.quantity.required' => 'Số lượng ở dòng :position không được để trống',
            'variants.*.quantity.integer' => 'Số lượng ở dòng :position phải là số nguyên',
            'variants.*.quantity.min' => 'Số lượng ở dòng :position phải lớn hơn hoặc bằng 0',
        ];
    }
}
