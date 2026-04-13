<?php

namespace App\Http\Requests\Material;

use Illuminate\Foundation\Http\FormRequest;

class VideoModuleRequest extends FormRequest
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
    public function rules()
    {
        return [
            'user_id' => 'required',
            'category_id' => 'required',
            'title' => 'required|string|max:191',
            'thumbnail' => 'sometimes',
            'description' => 'required',
            'order' => 'required|numeric',
            'price_type' => 'required',
            'price_before_discount' => 'required_if:price_type,2',
            'price_after_discount' => 'required_if:price_type,2',
            'period_type'   => 'sometimes',
            'active_period' => 'required_with:period_type',
            'status' => 'required',
        ];
    }

    public function attributes()
    {
        return [
            'user_id' => __('video_module.user_id'),
            'category_id' => __('video_module.category_id'),
            'title' => __('video_module.title'),
            'thumbnail' => __('video_module.thumbnail'),
            'description' => __('video_module.description'),
            'order' => __('video_module.order'),
            'price_type' => __('video_module.price_type'),
            'price_before_discount' => __('video_module.price_before_discount'),
            'price_after_discount' => __('video_module.price_after_discount'),
            'period_type' => __('video_module.period_type'),
            'active_period' => __('video_module.active_period'),
            'status' => __('video_module.status'),
        ];
    }
}
