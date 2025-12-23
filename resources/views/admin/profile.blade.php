@extends('layouts.app')
@section('content')
    <div class="container mt-2">
        <h1>Admin Profile</h1>
        <form action="{{ route('update.admin.profile') }}" id="formemployeedata" method="post" enctype="multipart/form-data">
            @csrf
            <div class="form-group my-2">
                <label for="Name" class="form-label">Name</label>
                <input type="text" class="form-control" id="name" name="name" value="{{ Auth::user()->name ?? null }}"
                    placeholder="Enter name" required />
            </div>
            <div class="form-group my-2">
                <label for="Email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email" placeholder="Enter email"
                    value="{{ Auth::user()->email ?? null }}" required />
            </div>
            <div class="form-group my-2">
                <input type="submit" value="Update Profile " class="btn btn-primary" />
            </div>
        </form>
    </div>
    <div class="container mt-2">
        <h1>Change Password</h1>
        <form action="{{ route('change.password') }}" id="changepassword" method="post">
            @csrf
            <div class="form-group my-2">
                <div class="form-group my-2">
                    <label for="old_password" class="form-label">Old Password</label>
                    <input type="password" class="form-control" id="old_password" name="old_password"
                        placeholder="Enter old password" required />
                </div>
            </div>
            <div class="form-group my-2">
                <div class="form-group my-2">
                    <label for="new_password" class="form-label">New Password</label>
                    <input type="password" class="form-control" id="new_password" name="new_password"
                        placeholder="Enter new password" required />
                </div>
            </div>
            <div class="form-group my-2">
                <div class="form-group my-2">
                    <label for="confirm_password" class="form-label">Confirm Password</label>
                    <input type="password" class="form-control" id="confirm_password" name="confirm_password"
                        placeholder="Enter confirm password" required />
                </div>
            </div>

            <div class="form-group my-2">
                <input type="submit" value="Change Password " class="btn btn-primary" />
            </div>
        </form>
    </div>
@endsection
@push('scripts')
    <script>
        $("#formemployeedata").validate({
            rules: {
                name: {
                    required: true,
                },
                email: {
                    required: true,
                }
            },
            messages: {
                name: {
                    required: "please enter valid name"
                },
                email: {
                    required: "please enter valid email"
                },
            }
        });
    </script>
    <script>
        $("#changepassword").validate({
            rules: {
                old_password: {
                    required: true,
                },
                new_password: {
                    required: true,
                },
                confirm_password: {
                    required: true,
                    equalTo: new_password
                },
            },
            messages: {
                old_password: {
                    required: "please enter valid old_password"
                },
                new_password: {
                    required: "please enter valid new_password"
                },
                confirm_password: {
                    required: "please enter valid confirm_password",
                    equalTo: "new password and confirm password both must be match"
                },
            },
        });
    </script>
@endpush
