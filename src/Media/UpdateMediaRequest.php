<?php

declare(strict_types=1);

namespace Calexandar\Adminium\Media;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

final class UpdateMediaRequest extends FormRequest
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

            'title.*' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255', Rule::unique(Media::class)->ignore($this->route()->parameter('media'))],
            'description.*' => ['required', 'string', 'max:255'],
            'caption.*' => ['required', 'string', 'max:255'],
            'icon' => ['image', 'mimes:jpeg,png,jpg,gif,svg', 'max:1024'],
            'cover_image' => ['image', 'mimes:jpeg,png,jpg,gif,svg', 'max:1024'],
            'meta_title.*' => ['required', 'string', 'max:60'],
            'meta_description.*' => ['required', 'string', 'max:160'],
            'published' => ['required', 'boolean'],
        ];
    }
}
