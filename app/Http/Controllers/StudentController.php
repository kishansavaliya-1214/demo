<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Models\Student;
use App\Models\User;
use DataTables;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class StudentController extends Controller
{
    public function index(Request $request): JsonResponse|View
    {
        if ($request->ajax()) {
            $authUserId = auth()->id();
            $data = Student::with(['user'])
                ->whereHas('user', function ($query) use ($authUserId) {
                    $query->where('created_by', $authUserId);
                });

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('name', function ($row) {
                    return $row->user->name ?? '--';
                })
                ->addColumn('email', function ($row) {
                    return $row->user->email ?? '--';
                })
                ->addColumn('photo', function ($row) {
                    $url = asset("storage/students/$row->photo");

                    return '<img src="'.$url.'" border="0" width="100" height="100" class="img-rounded" align="center" />';
                })
                ->addColumn('action', function ($row) {

                    $btn = '<div class="d-flex" style="gap: 5px;"><a href="'.route('students.show', encrypt($row->id)).'" class="edit btn btn-primary btn-sm">View</a>';
                    $btn .= '<a href="'.route('students.edit', encrypt($row->id)).'" class="edit btn btn-warning btn-sm">Edit</a>';
                    $btn .= '<form action="'.route('students.destroy', encrypt($row->id)).'" id="deleteform-'.$row->id.'" method="post" class="d-inline">';

                    $btn .= '<input type="hidden" name="_token" value="'.csrf_token().'">';
                    $btn .= '<input type="hidden" name="_method" value="DELETE">';
                    $btn .= '<button type="submit" onclick="return showAlert(event)" class="btn btn-danger btn-sm">Delete</button>';
                    $btn .= '</form></div>';

                    return $btn;
                })
                ->filterColumn('name', function ($query, $keyword) {
                    $query->whereHas('user', function ($q) use ($keyword) {
                        $q->where('name', 'like', "%{$keyword}%");
                    });
                })
                ->filterColumn('email', function ($query, $keyword) {
                    $query->whereHas('user', function ($q) use ($keyword) {
                        $q->where('email', 'like', "%{$keyword}%");
                    });
                })
                ->rawColumns(['action', 'photo'])
                ->make(true);
        }

        return view('student.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('student.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(UserRequest $request): RedirectResponse
    {
        $validatedData = $request->validated();
        $user = new User;
        $user->name = $validatedData['name'];
        $user->email = $validatedData['email'];
        if (isset($validatedData['password'])) {
            $user->password = Hash::make($validatedData['password']);
        }
        $user->created_by = Auth::user()->id;
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
            $file->storeAs('students', $filename, 'public');
            // $file->move(public_path('images'), $filename);
            $student->photo = $filename;
        }
        $student->save();

        return redirect()->route('students.index')->with('success', 'student added successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show($id): RedirectResponse|View
    {
        try {
            $id = decrypt($id);
        } catch (\Throwable $th) {
            return redirect()->route('students.index')->with('error', 'Student Not Found');
        }
        $student = Student::findOrFail($id);

        return view('student.show', compact('student'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): RedirectResponse|View
    {
        try {
            $id = decrypt($id);
        } catch (\Throwable $th) {
            return redirect()->route('students.index')->with('error', 'Student Not Found');
        }
        $student = Student::findOrFail($id);

        return view('student.edit', compact('student'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UserRequest $request, $id): RedirectResponse
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
            if (Storage::disk('public')->exists('students/'.$student->photo)) {
                Storage::disk('public')->delete('students/'.$student->photo);
            }
            $file = $validatedData['photo'];
            $filename = time().'.'.$file->getClientOriginalExtension();
            $file->storeAs('students', $filename, 'public');
            // $file->move(public_path('images'), $filename);
            $student->photo = $filename;
        }
        $student->save();

        return redirect()->route('students.index')->with('success', 'student updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id): RedirectResponse
    {
        try {
            $id = decrypt($id);
        } catch (\Throwable $th) {
            return redirect()->route('students.index')->with('error', 'Student Not Found');
        }
        $student = Student::findOrFail($id);
        if (Storage::disk('public')->exists('students/'.$student->photo)) {
            Storage::disk('public')->delete('students/'.$student->photo);
        }
        if ($student->user) {
            $student->user->delete();
        }
        $student->delete();

        return redirect()->route('students.index')->with('success', 'student deleted successfully');
    }
}
