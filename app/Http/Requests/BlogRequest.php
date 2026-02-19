<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;

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
        if($this->isMethod('POST')) {
            $rules = [
                'title' => 'required|string|max:255',
                'slug' => 'required|string|unique:blogs,slug',
                'body' => 'required|string',
            ];
        }


        if ($this->isMethod('PUT') || $this->isMethod('PATCH')) {
            $blog = $this->route('blog');

            if (!$blog || !($blog instanceof \App\Models\Blog)) {
                return [];
            }

            $rules = [];

            // فقط فیلدهایی که در request وجود دارند رو اعتبارسنجی کن
            if ($this->has('title')) {
                $rules['title'] = 'sometimes|required|string|max:255';
            }

            if ($this->has('slug')) {
                $rules['slug'] = [
                    'sometimes',
                    'required',
                    'string',
                    'max:255',
                    Rule::unique('blogs', 'slug')->ignore($blog->id)
                ];
            }

            if ($this->has('body')) {
                $rules['body'] = 'sometimes|required|string';
            }

            return $rules;
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
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            response()->json([
                'success' => false,
                'message' => 'خطا در اعتبارسنجی داده‌ها',
                'errors' => $validator->errors(),
                'status' => 422
            ], 422)
        );
    }
}


