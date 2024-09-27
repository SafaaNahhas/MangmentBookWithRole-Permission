<?php

namespace App\Http\Requests\permissionRequest;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePermissionRequest extends FormRequest
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
            'name' => 'sometimes|required|string|max:255|unique:permissions,name,' . $this->route('id'), // الحصول على معرف الصلاحية من رابط الطلب
            'description' => 'nullable|string|max:255',
        ];
    }
    public function messages()
    {
        return [
            'name.required' => 'The name field is required.',
            'name.unique' => 'The name has already been taken.',
            'description.string' => 'The description must be a string.',
        ];
    }
}
