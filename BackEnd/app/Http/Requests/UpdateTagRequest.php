<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTagRequest extends FormRequest
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
        $id = $this->route('tag')->id;
        return [
            'name' => 'required|string|max:255',
            'slug' => 'required|unique:tags,slug,' . $id,
            'status' => 'required|boolean',
        ];
    }
    public function messages(): array
    {
        return [
            'name.required' => 'Tên Nhãn không được để trống',
            'slug.unique'   => 'Nhãn đã tồn tại đã tồn tại',
        ];
    }
}
