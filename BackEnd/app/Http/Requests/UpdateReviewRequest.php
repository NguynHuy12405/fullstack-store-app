<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateReviewRequest extends FormRequest
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
            'rating'  => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
            'status' => 'required|boolean',
        ];
    }
    public function messages(): array
    {
        return [
            'rating.required' => 'Vui lòng chọn số sao đánh giá.',
            'rating.integer'  => 'Đánh giá phải là số nguyên.',
            'rating.min'      => 'Đánh giá thấp nhất là 1 sao.',
            'rating.max'      => 'Đánh giá cao nhất là 5 sao.',
            'comment.max'     => 'Nội dung bình luận không được vượt quá 1000 ký tự.',
            'status.required' => 'Vui lòng chọn trạng thái.',
            'status.boolean' => 'Trạng thái không hợp lệ.',
        ];
    }
}
