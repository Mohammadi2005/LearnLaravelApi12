<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BlogRequest extends FormRequest
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
        $rules = [
            'title' => 'required|string|max:255',
            'body' => 'required|string',
        ];

        if ($this->isMethod('PUT') || $this->isMethod('PATCH')) {
            $blog = $this->route('blog');
            $rules['slug'] = 'required|string|max:255|unique:blogs,slug,' . $blog->id;
        } else {
            $rules['slug'] = 'required|string|max:255|unique:blogs,slug';
        }

        return $rules;
    }
    public function messages(): array
    {
        return [
            'title.required' => 'عنوان مطلب الزامی است',
            'title.max' => 'عنوان نباید بیشتر از ۲۵۵ کاراکتر باشد',
            'slug.required' => 'نامک (slug) الزامی است',
            'slug.unique' => 'این نامک قبلاً استفاده شده است',
            'body.required' => 'متن مطلب الزامی است',
        ];
    }

}


