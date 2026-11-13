<?php

declare(strict_types=1);

namespace Calexandar\Adminium\Users;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules;

final class CreateUserRequest extends FormRequest
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

            'name' => ['required', 'string', 'max:255'],
            'title' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'lowercase',
                'email',
                'max:255',
                'unique:'.adminium_user_model(),
            ],
            'password' => ['required', 'confirmed',  Rules\Password::defaults()],
            'permissions' => ['required', 'array'],
            'avatar' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,webp', 'max:1024'],
        ];
    }
}
