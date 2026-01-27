<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateOrderRequest extends FormRequest
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
            'user_id' => 'prohibited',
            'name' => 'required|string|max:255',
            'phone' => 'required|digits:10',
            'address' => 'required|string|max:255',
            'admin_id' => 'nullable|exists:users,id',
            'shipper_id' => 'nullable|exists:users,id',
            'order_status_id' => 'required|exists:order_statuses,id',
            'ship_status_id' => 'required|exists:ship_statuses,id',
            'message' => 'nullable|string|max:1000',
        ];
    }
    public function messages(): array
    {
        return [
            'user_id.prohibited' => 'Bạn không được phép thay đổi chủ sở hữu hóa đơn',
            'name.required' => 'Tên người nhận không được để trống.',
            'phone.required' => 'Số điện thoại không được để trống.',
            'phone.digits' => 'Số điện thoại không đúng định dạng.',
            'address.required' => 'Địa chỉ nhận hàng không được để trống.',
            'order_status_id.exists' => 'Trạng thái đơn hàng không hợp lệ.',
            'ship_status_id.exists'  => 'Trạng thái vận chuyển không hợp lệ.',
        ];
    }
}
