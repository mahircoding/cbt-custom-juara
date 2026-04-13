<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Setting\Setting;
use App\Models\MasterData\Student;

class UserRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules(): array
    {
        $setting = Setting::first();
        $fields = $setting->authentication_field ?? [];
        
        $rules = [];
        $isUpdate = request()->isMethod('PUT');
        
        // Ambil student berdasarkan user_id
        $student = Student::where('user_id', request()->id)->first();
        $studentId = $student ? $student->id : null;
        $userId = request()->id;

        foreach ($fields as $field) {
            if ($field['is_active'] == '1') {
                $fieldName = $field['name'];
                $isRequired = $field['is_required'] == '1';
                $rule = $isRequired ? 'required' : 'nullable';
                
                switch ($fieldName) {
                    case 'code':
                        if (request()->level == 2) {
                            $rule = $isRequired ? 'required' : 'nullable';
                        } else {
                            $rule = 'nullable';
                        }
                        $rule .= '|max:255|unique:users,code,' . $userId;
                        break;
                    case 'class_name':
                        if (request()->level == 2) {
                            $rule = $isRequired ? 'required' : 'nullable';
                        } else {
                            $rule = 'nullable';
                        }
                        
                        $rule .= '|string|max:255';
                        break;
                    case 'code':
                        if (request()->level == 2) {
                            $rule = $isRequired ? 'required' : 'nullable';
                        } else {
                            $rule = 'nullable';
                        }
                        $rule .= '|max:255|unique:users,code,' . $userId;
                        break;
                    case 'photo':
                        if ($isUpdate) {
                            $rule = 'sometimes|nullable';
                        } else {
                            $rule = 'required';
                        }
                        $rule .= '|image|mimes:jpeg,png,jpg|max:2048';
                        break;
                    case 'email':
                        $rule .= '|max:255|unique:users,email,' . $userId;
                        break;
                    case 'phone_number':
                        if ($isUpdate) {
                            $rule .= '|numeric|unique:students,phone_number,' . $studentId;
                        } else {
                            $rule .= '|numeric|unique:students,phone_number';
                        }
                        break;
                    case 'username':
                        $rule .= '|string|max:255|unique:users,username,' . $userId;
                        break;
                    case 'password':
                        if ($isUpdate) {
                            $rule = 'nullable|string|min:8|confirmed';
                        } else {
                            $rule .= '|string|min:8|confirmed';
                        }
                        break;
                    case 'province_id':
                    case 'city_id':
                    case 'district_id':
                    case 'village_id':
                    case 'address':
                    case 'gender':
                        break;
                }
                
                $rules[$fieldName] = $rule;
            }
        }

        $rules['member_type'] = 'required_if:level,2';
        $rules['level'] = 'required|in:1,2,3';
        $rules['category_ids'] = $setting->category_access == 2 && request()->level == 2 ? 'required' : 'sometimes';
        $rules['is_active'] = 'required';
        
        return $rules;
    }

    public function attributes(): array
    {
        $setting = Setting::first();
        $fields = $setting->authentication_field ?? [];
        
        $attributes = [];

        foreach ($fields as $field) {
            if ($field['is_active'] == '1') {
                $attributes[$field['name']] = $field['translate'];
            }
        }

        $attributes['category_ids'] = 'Kategori Peminatan';
        $attributes['member_type'] = 'Tipe Member';
        $attributes['level'] = 'Level';
        $attributes['is_active'] = 'Status';

        return $attributes;
    }
}
