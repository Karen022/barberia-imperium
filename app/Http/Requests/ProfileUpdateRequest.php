<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'lowercase',
                'email',
                'max:255',
                Rule::unique(User::class)->ignore($this->user()->id),
            ],
            'phone' => [
                'nullable', 
                'string', 
                'regex:/^9[6-9][1-6]\d{6}$/'
            ],
            'document_type' => ['nullable', 'in:CI,RUC'],
            'document_number' => ['nullable', 'string', 'regex:/^\d+$/','max:20'],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
            'profile_image' => ['nullable', 'image', 'max:2048'],
        ];
    }
}
