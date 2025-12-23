<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class StudentAPIController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $students = Student::with(['courses', 'user'])->get();

        return response()->json([
            'status' => 'true',
            'message' => 'Students Retrieved Successfully',
            'data' => $students,
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|max:255|email|unique:users',
            'password' => 'required|min:8',
            'phone' => 'required|max:10',
            'age' => 'required',
            'gender' => 'required',
            'address' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => 'false',
                'message' => $validator->errors()->first(),
            ], 422);
        }
        $user = new User;
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->role = 'student';
        $user->created_by = 2;
        $user->save();
        $student = new Student;
        $student->user_id = $user->id;
        if ($request->photo) {
            $file = $request->file('photo');
            $filename = time().'.'.$file->getClientOriginalExtension();
            $file->move(public_path('images'), $filename);
            $student->photo = $filename;
        }
        $student->phone = $request->phone;
        $student->age = $request->age;
        $student->gender = $request->gender;
        $student->address = $request->address;
        $student->save();

        return response()->json([
            'status' => 'true',
            'message' => 'student created successfully',
        ], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $students = Student::with(['courses', 'user'])->find($id);

        return response()->json([
            'status' => 'true',
            'message' => 'Students Retrieved Successfully',
            'data' => $students,
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|max:255|email',
            'phone' => 'required|max:10',
            'age' => 'required',
            'gender' => 'required',
            'address' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => 'false',
                'message' => $validator->errors()->first(),
            ], 422);
        }
        $student = Student::find($id);
        $student->user->name = $request->name;
        $student->user->email = $request->email;
        if ($request->password) {
            $student->user->password = Hash::make($request->password);
        }
        $student->user->save();
        if ($request->photo) {
            if (File::exists(public_path('images/'.$student->photo))) {
                File::delete(public_path('images/'.$student->photo));
            }
            $file = $request->file('photo');
            $filename = time().'.'.$file->getClientOriginalExtension();
            $file->move(public_path('images'), $filename);
            $student->photo = $filename;
        }
        $student->phone = $request->phone;
        $student->age = $request->age;
        $student->gender = $request->gender;
        $student->address = $request->address;
        $student->save();

        return response()->json([
            'status' => 'true',
            'message' => 'student updated successfully',
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $student = Student::find($id);
        $student->user()->delete();
        if ($student->courses) {
            foreach ($student->courses as $course) {
                $course->delete();
            }
        }
        $student->delete();

        return response()->json([
            'status' => 'true',
            'message' => 'student deleted successfully',
        ], 200);
    }
}
