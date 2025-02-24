<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProfileUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Atur menjadi true agar user bisa mengupdate profil mereka
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $this->user()->id],
            'role' => ['required', 'string'], // Validasi untuk role
            'alamat' => ['required', 'string'], // Validasi untuk alamat
            'telepon' => ['required', 'string'], // Validasi untuk telepon
        ];
    }
}
