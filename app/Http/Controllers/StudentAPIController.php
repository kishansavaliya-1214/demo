<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Models\Student;
use App\Models\User;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;

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
    public function store(UserRequest $request)
    {
        $validatedData = $request->validated();
        $user = new User;
        $user->name = $validatedData['name'];
        $user->email = $validatedData['email'];
        if (isset($validatedData['password'])) {
            $user->password = Hash::make($validatedData['password']);
        }
        $user->created_by = auth()->user()->id;
        $user->save();
        $student = new Student;
        $student->user_id = $user->id;
        $student->phone = $validatedData['phone'];
        $student->gender = $validatedData['gender'];
        $student->age = $validatedData['age'];
        $student->address = $validatedData['address'];
        if ($validatedData['photo']) {
            $file = $validatedData['photo'];
            $filename = time().'.'.$file->getClientOriginalExtension();
            $file->move(public_path('images'), $filename);
            $student->photo = $filename;
        }
        $student->save();

        return response()->json([
            'status' => 'true',
            'message' => 'student created successfully',
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $students = Student::with(['courses', 'user'])->findOrFail($id);

        return response()->json([
            'status' => 'true',
            'message' => 'Students Retrieved Successfully',
            'data' => $students,
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UserRequest $request, $id)
    {
        $validatedData = $request->validated();
        $student = Student::findOrFail($id);
        $student->user->name = $validatedData['name'];
        $student->user->email = $validatedData['email'];
        $student->user->save();
        $student->phone = $validatedData['phone'];
        $student->gender = $validatedData['gender'];
        $student->age = $validatedData['age'];
        $student->address = $validatedData['address'];
        if (isset($validatedData['photo']) && ! empty($validatedData['photo'])) {
            if (File::exists(public_path('images/'.$student->photo))) {
                File::delete(public_path('images/'.$student->photo));
            }
            $file = $validatedData['photo'];
            $filename = time().'.'.$file->getClientOriginalExtension();
            $file->move(public_path('images'), $filename);
            $student->photo = $filename;
        }
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
        $student = Student::findOrFail($id);
        if (File::exists(public_path('images/'.$student->photo))) {
            File::delete(public_path('images/'.$student->photo));
        }
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
