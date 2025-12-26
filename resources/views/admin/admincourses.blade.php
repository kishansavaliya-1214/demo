@extends('layouts.app')
@section('content')
    <div class="container mt-2 table-responsive">
        <table class="table table-striped table-bordered data-table" id="courseList">
            <thead>
                <tr>
                    <th scope="col">Id</th>
                    <th scope="col">Name</th>
                    <th scope="col">Image</th>
                    <th scope="col">Description</th>
                    <th scope="col">Number Of Hours</th>
                    <th scope="col">Course Fee</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>
@endsection
@push('scripts')
    <script type="text/javascript">
        $(function () {
            var table = $('#courseList').DataTable({
                ordering: true,
                processing: true,
                serverSide: true,
                searchable: true,
                ajax: "{{ route('admin.courses') }}",
                columns: [
                    { data: 'id', name: 'id' },
                    { data: 'name', name: 'name' },
                    { data: 'image', name: 'image' },
                    { data: 'description', name: 'description' },
                    { data: 'number_of_hours', name: 'number_of_hours' },
                    { data: 'course_fee', name: 'course_fee' },
                ],
                order: [[0, 'asc']]
            });
            $("#dt-search-0").prop("placeholder", "Search");
        });
    </script>
@endpush
