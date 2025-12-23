<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Student;
use App\Models\StudentCourses;
use DataTables;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CourseController extends Controller
{
    public function displaycourses()
    {
        $courses = Course::get();
        $student = Student::where('user_id', Auth::user()->id)->firstOrFail();
        $studentCourseids = StudentCourses::where('student_id', $student->id)->pluck('course_id')->toArray();

        return view('Course.index', compact('courses', 'studentCourseids'));
    }

    public function activateStudentCourse(Request $request)
    {
        $courseid = $request->course_id ?? 0;
        $student = Student::where('user_id', Auth::user()->id)->firstOrFail();
        $studentCourse = new StudentCourses;
        $studentCourse->student_id = $student->id;
        $studentCourse->course_id = $courseid;
        $studentCourse->save();

        return response()->json([
            'message' => 'Course Activated Successfully!',
            'status' => 'success',
            'course_id' => $studentCourse->course_id,
        ]);
    }

    public function allCourses(Request $request)
    {
        if ($request->ajax()) {
            $data = Course::with(['students']);

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('image', function ($row) {
                    $url = $row->image;

                    return '<img src="'.$url.'" border="0" width="100" class="img-rounded" align="center" />';
                })
                ->rawColumns(['image'])
                ->make(true);
        }

        return view('admin.admincourses');
    }
}
