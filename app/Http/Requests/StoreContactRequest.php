<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreContactRequest extends FormRequest
{
    protected $errorBag = 'add';    
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
            'name' => 'required|string|max:255|unique:contacts,name',
            'url' => 'required|string|max:255',
            'status' => 'nullable|boolean',
        ];
    }
    public function messages(): array
    {
        return [
            'name.required' => 'Tên liên hệ không được để trống',
            'name.unique' => 'Tên liên hệ này đã tồn tại.',
            'url.required' => 'Tên đường dẫn không được để trống',
            'url.max' => 'Đường dẫn tối đa 255 ký tự',
        ];
    }
}
