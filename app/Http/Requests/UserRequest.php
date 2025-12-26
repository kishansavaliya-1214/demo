<?php

namespace App\Http\Requests;

use App\Models\Student;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class UserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $studentId = $this->route('student');
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
            $student = Student::find($studentId);
            $userId = $student ? $student->user_id : auth()->user()->id; // auth id used for update student profile

            $rules['email'] = 'required|email|max:255|unique:users,email,'.$userId;
            $rules['photo'] = [
                'nullable',
                'image',
                'mimes:jpeg,jpg',
                'max:2048',
            ];
        }

        return $rules;
    }
}
