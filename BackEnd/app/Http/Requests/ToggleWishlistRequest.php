<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class ToggleWishlistRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'product_id' => 'required|exists:products,id',
            'variant_id' => 'nullable|exists:variants,id',
        ];
    }

    public function messages(): array
    {
        return [
            'product_id.required' => 'Thiếu sản phẩm.',
            'product_id.exists'   => 'Sản phẩm không tồn tại.',
            'variant_id.exists'   => 'Biến thể không tồn tại.',
        ];
    }
}
