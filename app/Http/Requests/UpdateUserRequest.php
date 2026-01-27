<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
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
        $id = $this->route('user')->id;

        return [
            'name' => 'required|string|max:255',
            'email' => 'prohibited',
            'phone' => 'required|digits:10|unique:users,phone,' . $id,
            'password' => 'prohibited',
            'role_id' => 'required|exists:roles,id',
            'status' => 'required|boolean',
            'gender' => 'required|boolean',
            'review' => 'required|boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Tên người dùng không được để trống',
            'email.unique' => 'Email đã tồn tại',
            'phone.required' => 'Số điện thoại là bắt buộc',
            'phone.unique' => 'Số điện thoại đã tồn tại',
        ];
    }
}
