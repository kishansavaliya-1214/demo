@extends('layouts.app')
@section('content')
    <div class="container mt-2">
        <h1>Admin Profile</h1>
        <form action="{{ route('update.admin.profile') }}" id="FormEmployeeData" method="post"
            enctype="multipart/form-data">
            @csrf
            <div class="form-group my-2">
                <label for="Name" class="form-label">Name</label>
                <input type="text" class="form-control" id="name" name="name" value="{{ old('name', Auth::user()->name) }}"
                    placeholder="Enter name" required />
                @error('name')
                    <span class="error">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group my-2">
                <label for="Email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email" placeholder="Enter email"
                    value="{{ old('email', Auth::user()->email) }}" required />
                @error('email')
                    <span class="error">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group my-2">
                <input type="submit" value="Update Profile " class="btn btn-primary" />
            </div>
        </form>
    </div>
    <div class="container mt-2">
        <h1>Change Password</h1>
        <form action="{{ route('change.password') }}" id="ChangePassword" method="post">
            @csrf
            <div class="form-group my-2">
                <div class="form-group my-2">
                    <label for="old_password" class="form-label">Old Password</label>
                    <input type="password" class="form-control" id="old_password" name="old_password"
                        placeholder="Enter old password" required />
                    @error('old_password')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
            </div>
            <div class="form-group my-2">
                <div class="form-group my-2">
                    <label for="new_password" class="form-label">New Password</label>
                    <input type="password" class="form-control" id="new_password" name="new_password"
                        placeholder="Enter new password" required />
                    @error('new_password')
                        <span class="error">{{ $message }}</span>
                    @enderror
                </div>
            </div>
            <div class="form-group my-2">
                <div class="form-group my-2">
                    <label for="confirm_password" class="form-label">Confirm Password</label>
                    <input type="password" class="form-control" id="confirm_password" name="confirm_password"
                        placeholder="Enter confirm password" required />
                    @error('confirm_password')
                        <span class="error">{{ $message }}</span>
                    @enderror
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
        $("#FormEmployeeData").validate({
            rules: {
                name: {
                    required: true,
                    maxlength: 255
                },
                email: {
                    required: true,
                    email: true,
                    remote: {
                        url: '{{ route('validate.email') }}',
                        type: 'post',
                        data: {
                            email: function () {
                                return $("#email").val();
                            },
                            _token: function () {
                                return "{{ csrf_token() }}";
                            }
                        }
                    }
                },
            },
            messages: {
                name: {
                    required: "please enter valid name",
                    maxlength: "Only 255 characters are allowed"
                },
                email: {
                    required: "please enter valid email",
                    email: "Please enter a valid email address",
                    remote: "This email address is already registered."
                },
            }
        });
    </script>
    <script>
        $("#ChangePassword").validate({
            rules: {
                old_password: {
                    required: true,
                },
                new_password: {
                    required: true,
                    minlength: 8,
                    strongPassword: true
                },
                confirm_password: {
                    required: true,
                    equalTo: new_password
                },
            },
            messages: {
                old_password: {
                    required: "please enter valid old password"
                },
                new_password: {
                    required: "please enter valid new password",
                    minlength: "password must be atleast 8 characters"
                },
                confirm_password: {
                    required: "please enter valid confirm password",
                    equalTo: "new password and confirm password both must be match"
                },
            },
        });
    </script>
@endpush
