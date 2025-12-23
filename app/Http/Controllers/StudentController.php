<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\User;
use DataTables;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;

class StudentController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $authUserId = auth()->id();
            $data = Student::with(['user'])
                ->whereHas('user', function ($query) use ($authUserId) {
                    // Add condition to the related 'users' table query
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
                    $url = asset("images/$row->photo");

                    return '<img src="'.$url.'" border="0" width="100" class="img-rounded" align="center" />';
                })
                ->addColumn('action', function ($row) {

                    $btn = '<div class="d-flex" style="gap: 5px;"><a href="'.route('students.show', $row->id).'" class="edit btn btn-primary btn-sm">View</a>';
                    $btn .= '<a href="'.route('students.edit', $row->id).'" class="edit btn btn-warning btn-sm">Edit</a>';
                    $btn .= '<form action="'.route('students.destroy', $row->id).'" id="deleteform-'.$row->id.'" method="post" class="d-inline">';

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
    public function create()
    {
        return view('student.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|unique:users|email',
            'password' => 'required',
            'phone' => 'required|numeric|digits:10',
            'age' => 'required',
            'gender' => 'required',
            'address' => 'required',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->errors());
        }
        $user = new User;
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = bcrypt($request->password);
        $user->role = 'student';
        $user->created_by = Auth::user()->id;
        $user->save();
        $student = new Student;
        $student->user_id = $user->id;
        $student->phone = $request->phone;
        $student->gender = $request->gender;
        $student->age = $request->age;
        $student->address = $request->address;
        if ($request->photo) {
            $file = $request->file('photo');
            $filename = time().'.'.$file->getClientOriginalExtension();
            $file->move(public_path('images'), $filename);
            $student->photo = $filename;
        }
        $student->save();

        return redirect()->route('students.index')->with('success', 'student added successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $student = Student::find($id);

        return view('student.show', compact('student'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $student = Student::find($id);

        return view('student.edit', compact('student'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'phone' => 'required|numeric|digits:10',
            'age' => 'required',
            'gender' => 'required',
            'address' => 'required',
        ]);
        $student = Student::find($id);
        $student->phone = $request->phone;
        $student->gender = $request->gender;
        $student->age = $request->age;
        $student->address = $request->address;
        if ($request->photo) {
            if (File::exists(public_path('images/'.$student->photo))) {
                File::delete(public_path('images/'.$student->photo));
            }
            $file = $request->file('photo');
            $filename = time().'.'.$file->getClientOriginalExtension();
            $file->move(public_path('images'), $filename);
            $student->photo = $filename;
        }
        $student->save();
        $user = User::find($student->user_id);
        $user->name = $request->name;
        $user->email = $request->email;
        $user->save();

        return redirect()->route('students.index')->with('success', 'student updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $student = Student::find($id);
        if (File::exists(public_path('images/'.$student->photo))) {
            File::delete(public_path('images/'.$student->photo));
        }
        $user = User::find($student->user_id);
        $user->delete();
        $student->delete();

        return redirect()->route('students.index')->with('success', 'student deleted successfully');
    }
}
