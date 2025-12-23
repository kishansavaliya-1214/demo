@extends('layouts.app')
@section('content')
    <div class="row">
        <h1>Activated Course</h1>
        @forelse (Auth::user()->student->courses as $course)
            <div class="col-md-4">
                <div class="card" data-course-id="{{ $course->id }}">
                    <img src="{{ $course->image }}" class="card-img-top" alt="...">
                    <div class="card-body">
                        <h5 class="card-title">{{$course->name ?? null }}</h5>
                        <p class="card-text">{{$course->description ?? null }}</p>
                        <div>Numbers Of Hours : {{$course->numberofhours ?? null }}</div>
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
