<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateContactRequest extends FormRequest
{

    protected $errorBag = 'edit';
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
        $id = $this->route('contact')->id;
        return [
            'name' => 'required|string|max:255|unique:contacts,name,' . $id,
            'url' => 'required|string|max:255',
            'status' => 'required|boolean',
        ];
    }
    public function messages(): array
    {
        return [
            'name.required' => 'Tên trạng thái giao hàng không được để trống.',
            'name.unique' => 'Tên liên hệ này đã tồn tại.',
            'url.required' => 'Đường dẫn không được để trống',
            'status.required' => 'Trạng thái không được để trống',
            'status.boolean' => 'Trạng thái không hợp lệ',
        ];
    }
}
