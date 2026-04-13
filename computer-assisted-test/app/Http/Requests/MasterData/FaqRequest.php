<?php

namespace App\Http\Requests\MasterData;

use Illuminate\Foundation\Http\FormRequest;

class FaqRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'user_id' => 'required',
            'question' => 'required|string|max:191',
            'answer' => 'required'
        ];
    }

    public function attributes()
    {
        return [
            'user_id' => __('faq.user_id'),
            'question' => __('faq.question'),
            'answer' => __('faq.answer'),
        ];
    }
}
