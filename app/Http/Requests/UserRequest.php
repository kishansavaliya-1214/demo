<?php

namespace App\Http\Requests;

use App\Models\Student;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class UserRequest extends FormRequest
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
        $user_id = $this->route('student') ?? 0;
        $stud = Student::find($user_id);
        $rules = [
            'name' => 'required|string|max:255',
            'phone' => 'required|numeric|digits:10',
            'age' => 'required|integer|min:18|max:60',
            'gender' => ['required', 'in:male,female'],
            'address' => 'required',
        ];
        if ($this->isMethod('post')) {
            $rules['password'] = [
                'required',
                Password::min(8)->letters()->mixedCase()->numbers()->symbols(),
            ];
            $rules['email'] = 'required|max:255|email|unique:users';
            $rules['photo'] = [
                'required',
                'image',
                'mimes:jpeg,jpg',
                'max:2048',
            ];
        }

        if ($this->isMethod('put') || $this->isMethod('patch')) {
            $rules['password'] = [
                'nullable',
                Password::min(8)->letters()->mixedCase()->numbers()->symbols(),
            ];
            $rules['email'] = 'required|email|max:255|unique:users,email,'.$stud->user_id ?? $user_id;

            $rules['photo'] = [
                'image',
                'mimes:jpeg,jpg',
                'max:2048',
            ];
        }

        return $rules;
    }
}
