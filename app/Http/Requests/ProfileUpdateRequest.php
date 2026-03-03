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
            'phone' => ['required', 'string', 'min:11', 'max:15', 'regex:/[0-9]+$/'],
        ];
    }

    public function messages(): array
    {
        return [
            'phone.min' => 'Nomor WhatsApp/HP minimal harus 11 angka.',
            'phone.max' => 'Nomor WhatsApp/HP maksimal 15 angka.',
            'phone.regex' => 'Nomor WhatsApp/HP hanya boleh berisi angka.',
        ];
    }
}
