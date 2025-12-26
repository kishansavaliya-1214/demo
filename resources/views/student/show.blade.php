@extends('layouts.app')
@section('content')
    <a href="{{ route('students.index') }}" class="btn btn-secondary my-2">Back</a>
    <h1>Student Info</h1>
    <div class="table-responsive">
        <table class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th scope="col">Name</th>
                    <th scope="col">Image</th>
                    <th scope="col">Email</th>
                    <th scope="col">Age</th>
                    <th scope="col">Gender</th>
                    <th scope="col">Address</th>
                    <th scope="col">Phone</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>{{ $student->user->name }}</td>
                    <td><img src="{{ asset('storage/students/' . $student->photo) }}" height="100px" width="100px" alt=""></td>
                    <td>{{ $student->user->email }}</td>
                    <td>{{ $student->age }}</td>
                    <td>{{ $student->gender }}</td>
                    <td>{{ $student->address }}</td>
                    <td>{{ $student->phone }}</td>
                </tr>
            </tbody>
        </table>
    </div>
    <div class="row">
        <h1>Course Info</h1>
        @forelse ($student->courses as $course)
            <div class="col-md-4">
                <div class="card" data-course-id="{{ $course->id }}">
                    <img src="{{ $course->image }}" class="card-img-top" alt="...">
                    <div class="card-body">
                        <h5 class="card-title">{{$course->name ?? null }}</h5>
                        <p class="card-text">{{$course->description ?? null }}</p>
                        <div>Numbers Of Hours : {{$course->number_of_hours ?? null }}</div>
                        <div>Fees: {{$course->course_fee ?? null }}</div>
                        <button class="btn btn-primary" disabled>Activated</button>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-md-4">
                No Course Found
            </div>
        @endforelse
    </div>
@endsection
