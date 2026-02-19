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
                'title' => 'required|string|min:5|max:255',
                'slug' => 'required|string|unique:blogs,slug',
                'body' => 'required|string',
                'image' => 'sometimes|required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
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

            if ($this->has('image')) {
                $rules['image'] = 'sometimes|required|image|mimes:jpeg,png,jpg,gif,svg|max:2048';
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
            'title.min' => "عنوان نباید کمتر از 5 کاراکتر باشد",
            'slug.required' => 'نامک (slug) الزامی است',
            'slug.unique' => 'این نامک قبلاً استفاده شده است',
            'body.required' => 'متن مطلب الزامی است',
            'image.required' => "انتخاب عکس الزامی است",
            'image.mimes' => 'jpeg,png,jpg,gif,svg باشد فرمت فایل باید'
        ];
    }
        //فارسی سازس کردن
        //
        //php artisan lang:publish
        //دستور بالا رو میزنم ی یک پوشه lang یاد که یک فایل
        //validation داره اونو میدم به چت تا ترجمه کنه و وعد توی همون پوسه پوشه en رو کپی میکنم با نام fa ذخیره میکنم و و ترجمه شده رو
        //میدم به validation عد توی فایل env
        //#APP_LOCALE=en
        //APP_LOCALE=fa
        //می کنم و بعد پیام ها رو فارسی میده

//    ========================

//4:52  رضا کوهساری
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


