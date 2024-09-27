<?php

namespace App\Http\Requests\RoleRequest;

use Illuminate\Foundation\Http\FormRequest;

class AssignPermissionsRequest extends FormRequest
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
            'permissions' => 'required|array',
            'permissions.*' => 'exists:permissions,id',
        ];
    }
    public function messages()
    {
        return [
            'permissions.required' => 'The permissions field is required.',
            'permissions.array' => 'The permissions must be an array.',
            'permissions.*.exists' => 'The selected permission is invalid.',
        ];
    }
}
