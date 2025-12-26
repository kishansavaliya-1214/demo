<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Models\Course;
use App\Models\Student;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Illuminate\Validation\Rules\Password;

class DashboardController extends Controller
{
    public function index(): View
    {
        $totalStudents = User::students()->count();
        $baseStudentQuery = User::students();
        $totalMaleStudents = (clone $baseStudentQuery)
            ->whereHas('student', function ($query) {
                $query->where('gender', 'male');
            })->count();
        $totalFemaleStudents = (clone $baseStudentQuery)
            ->whereHas('student', function ($query) {
                $query->where('gender', 'female');
            })->count();
        $totalCources = Course::count();
        $latestStudents = (clone $baseStudentQuery)->with(['student'])->latest()->take(5)->get();

        return view('admin.dashboard', compact('totalStudents', 'latestStudents', 'totalMaleStudents', 'totalFemaleStudents', 'totalCources'));
    }

    public function studentDashboard(): View
    {
        return view('student.dashboard');
    }

    public function profile(): View
    {
        return view('student.profile');
    }

    public function adminProfile(): View
    {
        return view('admin.profile');
    }

    public function updateAdminProfile(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,'.Auth::user()->id,
        ]);
        $user = auth()->user();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->save();

        return redirect()->back()->with('success', 'profile updated successfully');
    }

    public function updateStudentProfile(UserRequest $request): RedirectResponse
    {
        $request->validated();
        $user = auth()->user();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->save();
        $student = $user->student;
        $student->phone = $request->phone;
        $student->age = $request->age;
        $student->gender = $request->gender;
        $student->address = $request->address;
        if ($request->photo) {
            if (Storage::disk('public')->exists('students/'.$student->photo)) {
                Storage::disk('public')->delete('students/'.$student->photo);
            }
            $file = $request->photo;
            $filename = time().'.'.$file->getClientOriginalExtension();
            $file->storeAs('students', $filename, 'public');
            // $file->move(public_path('images'), $filename);
            $student->photo = $filename;
        }
        $student->save();

        return redirect()->back()->with('success', 'profile updated successfully');
    }

    public function validateEmail(Request $request): JsonResponse
    {
        $email = $request->input('email');
        $studentId = $request->input('student_id');

        if ($studentId) {
            $student = Student::find($studentId);
            $excludeUserId = $student ? $student->user_id : null;
        } else {
            $excludeUserId = Auth::user()->id;
        }
        $userExists = User::where('email', $email)->where('id', '!=', $excludeUserId)
            ->first();

        if ($userExists) {
            return Response::json('This email address is already registered.');
        } else {
            return Response::json(true);
        }
    }

    public function changePassword(Request $request): RedirectResponse
    {
        $request->validate([
            'old_password' => 'required',
            'new_password' => ['required', Password::min(8)->letters()->mixedCase()->numbers()->symbols()],
        ]);

        if (! Hash::check($request->old_password, auth()->user()->password)) {
            return redirect()->back()->with('error', 'The current password does not match our records.');
        }

        if (strcmp($request->old_password, $request->new_password) == 0) {
            return redirect()->back()->with('error', 'New Password cannot be same as your current password. Please choose a different password.');
        }

        $user = Auth::user();
        $user->update([
            'password' => Hash::make($request->new_password),
        ]);

        return redirect()->back()->with('success', 'Password changed successfully!');
    }
}
