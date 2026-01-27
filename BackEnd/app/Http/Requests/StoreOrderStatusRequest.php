<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreOrderStatusRequest extends FormRequest
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
            'name' => 'required|string|max:255|unique:order_statuses,name',
        ];
    }
    public function messages(): array
    {
        return [
            'name.required' => 'Tên trạng thái đơn hàng không được để trống.',
            'name.unique' => 'Tên trạng thái đơn hàng này đã tồn tại.',
            'name.max' => 'Tên trạng thái có tối đa 255 ký tự',
        ];
    }
}
