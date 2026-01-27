<?php

namespace App\Http\Requests;

use App\Models\CartDetail; // Nhớ import model này
use App\Models\Variant;
use Illuminate\Foundation\Http\FormRequest;

class UpdateCartDetailRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'user_id' => 'prohibited',
            'variant_id' => 'sometimes|exists:variants,id',
            'quantity' => 'sometimes|integer|min:1',
            'status'   => 'sometimes|boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'variant_id.exists' => 'Biến thể không hợp lệ',
            'quantity.integer'  => 'Số lượng phải là số nguyên',
            'quantity.min'      => 'Số lượng tối thiểu là 1',
        ];
    }
}