<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreReviewRequest extends FormRequest
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
            'product_id' => 'required|exists:products,id',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|max:1000',
        ];
    }
    public function messages(): array
    {
        return [
            'product_id.required' => 'Sản phẩm đánh giá không được để trống.',
            'rating.required' => 'Vui lòng chọn số sao.',
            'comment.required' => 'Vui lòng nhập nhận xét.',
        ];
    }

    public function failedValidation(\Illuminate\Contracts\Validation\Validator $validator)
    {
        if (request()->expectsJson()) {
            throw new \Illuminate\Http\Exceptions\HttpResponseException(
                response()->json([
                    'status' => 'validation_error',
                    'errors' => $validator->errors(),
                    'message' => 'Vui lòng kiểm tra dữ liệu!'
                ], 422)
            );
        }

        parent::failedValidation($validator);
    }
}
