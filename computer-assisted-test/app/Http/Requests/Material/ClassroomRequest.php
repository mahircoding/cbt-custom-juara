<?php

namespace App\Http\Requests\Material;

use Illuminate\Foundation\Http\FormRequest;

class ClassroomRequest extends FormRequest
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
            'category_id' => 'required',
            'user_id' => 'required',
            'name' => 'required|string|max:191',
            'title' => 'required|string|max:191',
            'material' => 'required|string|max:191',
            'start_time' => 'required',
            'duration' => 'required|numeric',
            'description' => 'required',
            'price_type' => 'required',
            'price_before_discount' => 'required_if:price_type,2',
            'price_after_discount' => 'required_if:price_type,2',
            'link' => 'required|string|max:191',
            'status' => 'required',
        ];
    }

    public function attributes()
    {
        return [
            'category_id' => __('classroom.category_id'),
            'user_id' => __('classroom.user_id'),
            'name' => __('classroom.name'),
            'title' => __('classroom.title'),
            'material' => __('classroom.material'),
            'start_time' => __('classroom.start_time'),
            'duration' => __('classroom.duration'),
            'description' => __('classroom.description'),
            'link' => __('classroom.link'),
            'price_type' => __('module.price_type'),
            'price_before_discount' => __('module.price_before_discount'),
            'price_after_discount' => __('module.price_after_discount'),
            'status' => __('module.status'),
        ];
    }
}
