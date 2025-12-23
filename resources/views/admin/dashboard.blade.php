@extends('layouts.app')
@section('content')
    <h1>Welcome Admin {{ Auth::user()->name }}</h1>
    <div class="row">
        <div class="col-md-3">
            <div class="card shadow-lg border-light">
                <div class="card-body text-center">
                    <h5 class="card-title">Total Students</h5>
                    <p class="card-text"><i class="fa fa-group"></i> {{ $totalstudents ?? 0 }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-lg border-light">
                <div class="card-body text-center">
                    <h5 class="card-title">Total Male Students</h5>
                    <p class="card-text"><i class="fa fa-male"></i> {{ $totalmalestudents ?? 0 }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-lg border-light">
                <div class="card-body text-center">
                    <h5 class="card-title">Total FeMale Students</h5>
                    <p class="card-text"><i class="fa fa-female"></i> {{ $totalfemalestudents ?? 0 }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-lg border-light">
                <div class="card-body text-center">
                    <h5 class="card-title">Total Courses</h5>
                    <p class="card-text"><i class="fa fa-book"></i>{{ $totalcources ?? 0 }}</p>
                </div>
            </div>
        </div>
    </div>
    <div class="row mt-5">
        <div class="col-md-12">
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
                    @forelse ($lateststudents as $student)
                        <tr>
                            <td>{{ $student->name }}</td>
                            <td><img src="{{ asset('images/' . $student->student->photo ?? '') }}" width="150px"></td>
                            <td>{{ $student->email }}</td>
                            <td>{{ $student->student->age ?? null }}</td>
                            <td>{{ $student->student->gender ?? null }}</td>
                            <td>{{ $student->student->address ?? null }}</td>
                            <td>{{ $student->student->phone ?? null }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center">No Student Found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
@push('styles')
    <style>
        .card {
            height: 120px;
        }
    </style>
@endpush
