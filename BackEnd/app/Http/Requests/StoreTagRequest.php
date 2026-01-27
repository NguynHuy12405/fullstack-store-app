<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;

class StoreTagRequest extends FormRequest
{
    protected $errorBag = 'add';
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->isAdmin();
    }
    protected function prepareForValidation()
    {
        $this->merge([
            'slug' => Str::slug($this->name),
        ]);
    }
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:tags,slug',
            'status' => 'nullable|boolean',
        ];
    }
    public function messages(): array
    {
        return [
            'name.required' => 'Tên nhãn không được để trống',
            'slug.unique' => 'Nhãn đã tồn tại',
        ];
    }
}
