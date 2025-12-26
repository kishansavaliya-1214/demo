<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Student;
use App\Models\StudentCourses;
use DataTables;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class CourseController extends Controller
{
    public function displayCourses(): View
    {
        $courses = Course::get();
        $student = Student::where('user_id', Auth::user()->id)->firstOrFail();
        $studentCourseIds = StudentCourses::where('student_id', $student->id)->pluck('course_id')->toArray();

        return view('course.index', compact('courses', 'studentCourseIds'));
    }

    public function activateStudentCourse(Request $request): JsonResponse
    {
        $courseId = $request->course_id ?? 0;
        $student = Student::where('user_id', Auth::user()->id)->firstOrFail();
        $student->courses()->syncWithoutDetaching([$courseId]);

        return response()->json([
            'message' => 'Course Activated Successfully!',
            'status' => 'success',
            'course_id' => $courseId,
        ]);
    }

    public function allCourses(Request $request): JsonResponse|View
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
