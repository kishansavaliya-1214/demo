<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Student;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class DashboardController extends Controller
{
    public function index()
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

    public function studentDashboard()
    {
        return view('student.dashboard');
    }

    public function profile()
    {
        return view('student.profile');
    }

    public function adminProfile()
    {
        return view('admin.profile');
    }

    public function updateAdminProfile(Request $request)
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

    public function updateStudentProfile(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'phone' => 'required|min:10',
            'age' => 'required',
            'gender' => 'required',
            'address' => 'required',
        ]);
        $user = User::findOrFail(Auth::user()->id);
        $user->name = $request->name;
        $user->email = $request->email;
        $user->save();
        $student = Student::where('user_id', $user->id)->firstOrFail();
        $student->phone = $request->phone;
        $student->age = $request->age;
        $student->gender = $request->gender;
        $student->address = $request->address;
        if ($request->photo) {
            $file = $request->file('photo');
            $filename = time().'.'.$file->getClientOriginalExtension();
            $file->move(public_path('images'), $filename);
            $student->photo = $filename;
        }
        $student->save();

        return redirect()->back()->with('success', 'profile updated successfully');
    }

    public function changePassword(Request $request)
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
