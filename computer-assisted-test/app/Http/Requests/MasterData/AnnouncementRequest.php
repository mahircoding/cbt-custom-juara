<?php

namespace App\Http\Requests\MasterData;

use Illuminate\Foundation\Http\FormRequest;

class AnnouncementRequest extends FormRequest
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
            'title' => 'required|string|max:191',
            'description' => 'required'
        ];
    }

    public function attributes()
    {
        return [
            'user_id' => __('announcement.user_id'),
            'title' => __('announcement.title'),
            'description' => __('announcement.description'),
        ];
    }
}
