@extends('layouts.app')
@section('content')
    <div class="row">
        @foreach ($courses as $course)
            <div class="col-md-4">
                <div class="card" data-course-id="{{ $course->id }}">
                    <img src="{{ $course->image }}" class="card-img-top" alt="...">
                    <div class="card-body">
                        <h5 class="card-title">{{$course->name ?? null }}</h5>
                        <p class="card-text">{{$course->description ?? null }}</p>
                        <div>Numbers Of Hours : {{$course->numberofhours ?? null }}</div>
                        <div>Fees: {{$course->course_fee ?? null }}</div>
                        <button class="btn btn-primary" @if(in_array($course->id, $studentCourseids)) {{ "disabled" }}
                        @endif>@if(in_array($course->id, $studentCourseids)) {{ "Activated" }} @else {{ "Activate" }}
                            @endif</button>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endsection
@push('scripts')
    <script>
        $(document).ready(function () {
            $("button").click(function () {
                var clickedButton = $(this);
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                var course_id = $(this).parent().parent().data('course-id');
                $.ajax({
                    url: '{{ route('student.courses') }}',
                    method: 'POST',
                    data: { course_id: course_id },
                    success: function (response) {
                        if (response.status == "success") {
                            clickedButton.prop('disabled', true).text('Activated');
                            toastr.success(response.message);
                        } else {
                            toastr.success("issue while activate course");
                        }
                    }
                });

            });
        })
    </script>
@endpush
