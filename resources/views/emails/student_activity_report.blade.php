<!DOCTYPE html>
<html>

<head>
    <title>Student Course Activity Report</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">

    <style>
        table {
            border-collapse: collapse;
            width: 100%;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }
    </style>
</head>

<body>
    <h2>Student Course Activity Report</h2>
    <table class="table table-striped table-bordered">
        <thead>
            <tr>
                <th>Name</th>
                <th>Image</th>
                <th>Email</th>
                <th>Age</th>
                <th>Gender</th>
                <th>Address</th>
                <th>Phone</th>
                <th>Active Courses Count</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($students as $student)
                <tr>
                    <td>{{ $student->user->name ?? '--' }}</td>
                    <td><img src="{{ asset('images/' . $student->photo ?? null) }}" alt=""></td>
                    <td>{{ $student->user->email ?? '--' }}</td>
                    <td>{{ $student->age ?? '--' }}</td>
                    <td>{{ $student->gender ?? '--' }}</td>
                    <td>{{ $student->address ?? '--' }}</td>
                    <td>{{ $student->phone ?? '--' }}</td>
                    <td>{{ $student->active_courses_count ?? 0 }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>
