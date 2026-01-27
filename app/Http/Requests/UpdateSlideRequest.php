<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSlideRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->isAdmin();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title'       => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'url'         => 'nullable|string|max:255',
            'sort_order'  => 'required|integer',
            'status'      => 'required|boolean',
            'image'       => 'nullable|shop_image',
        ];
    }
    public function messages(): array
    {
        return [
            'url.required' => 'Đường dẫn liên kết không được để trống.',
            'status.required' => 'Vui lòng chọn trạng thái hiển thị.',
            'status.boolean' => 'Trạng thái chọn không hợp lệ.',
        ];
    }
}
