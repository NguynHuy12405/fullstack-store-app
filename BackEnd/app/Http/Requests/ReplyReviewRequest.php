<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class ReplyReviewRequest extends FormRequest
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
            'reply' => 'required|string|min:3|max:1000',
        ];
    }

    public function messages(): array
    {
        return [
            'reply.required' => 'Vui lòng nhập nội dung phản hồi.',
            'reply.min'      => 'Phản hồi tối thiểu :min ký tự.',
            'reply.max'      => 'Phản hồi tối đa :max ký tự.',
        ];
    }
}
