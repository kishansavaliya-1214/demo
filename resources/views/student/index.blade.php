@extends('layouts.app')
@section('content')
    <div class="container mt-2 table-responsive">
        <a href="{{ route('students.create') }}" class="btn btn-primary my-2">Add Student</a>
        <table class="table table-striped table-bordered data-table" id="studentList">
            <thead>
                <tr>
                    <th scope="col">Id</th>
                    <th scope="col">Name</th>
                    <th scope="col">Email</th>
                    <th scope="col">Age</th>
                    <th scope="col">Gender</th>
                    <th scope="col">Address</th>
                    <th scope="col">Phone</th>
                    <th scope="col">Image</th>
                    <th scope="col">Actions</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>
@endsection
@push('scripts')
    <script>
        function showAlert(event) {
            event.preventDefault();
            const form = event.target.closest('form')
            Swal.fire({
                title: 'Are you sure?',
                text: 'You wont be able to revert this!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, delete it!"
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: "Deleted!",
                        text: "student has been deleted",
                        icon: "success",
                        confirmButtonColor: "#3085d6",
                        cancelButtonColor: "#d33",
                        confirmButtonText: "ok"
                    }).then((re) => {
                        if (re.isConfirmed) {
                            form.submit();
                        }
                    })
                } else {
                    return false;
                }
            });
        }
    </script>
    <script type="text/javascript">
        $(function () {
            var table = $('#studentList').DataTable({
                ordering: true,
                processing: true,
                serverSide: true,
                searchable: true,
                ajax: "{{ route('students.index') }}",
                columns: [
                    { data: 'id', name: 'id' },
                    { data: 'name', name: 'name' },
                    { data: 'email', name: 'email' },
                    { data: 'age', name: 'age' },
                    { data: 'gender', name: 'gender' },
                    { data: 'address', name: 'address' },
                    { data: 'phone', name: 'phone' },
                    { data: 'photo', name: 'photo' },
                    { data: 'action', name: 'action', orderable: false, searchable: false },
                ]
            });

        });
    </script>
@endpush
