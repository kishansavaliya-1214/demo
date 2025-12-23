@extends('layouts.app')
@section('content')
    <div class="container mt-2">
        <h1>Student Profile</h1>
        <form action="{{ route('students.profile.update') }}" id="formemployeedata" method="post"
            enctype="multipart/form-data">
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
                <label for="photo" class="form-label">Photo</label>
                <input type="file" class="form-control" id="photo" name="photo" />
                @if (Auth::user()->student->photo)
                    <img src="{{ asset('images/' . Auth::user()->student->photo ?? null) }}" class="rounded mt-2" width="100px"
                        height="100px" alt="">
                @endif
            </div>
            <div class="form-group my-2">
                <label for="phone" class="form-label">Phone</label>
                <input type="number" class="form-control" id="phone" minlength="10"
                    value="{{ Auth::user()->student->phone ?? null }}" name="phone" placeholder="Enter phone" required />
            </div>
            <div class="form-group my-2">
                <label for="age" class="form-label">Age</label>
                <input type="number" class="form-control" id="age" name="age"
                    value="{{ Auth::user()->student->age ?? null }}" placeholder="Enter age" required />
            </div>
            <div class="form-group my-2">
                <label for="gender" class="form-label">gender :</label>
                <input type="radio" class="form-check-input me-2" name="gender" id="gender" value="male" @if (Auth::user()->student->gender == "male") {{ 'checked' }} @endif><label for="">Male</label>
                <input type="radio" class="form-check-input me-2" name="gender" id="gender" value="female" @if (Auth::user()->student->gender == "female") {{ 'checked' }} @endif /><label for="">FeMale</label>
                <br />
                <label id="gender-error" class="error" style="display:none;" for="gender">please select gender</label>
            </div>
            <div class="form-group my-2">
                <label for="address" class="form-label">Address</label>
                <textarea name="address" id="address" class="form-control" placeholder="Enter Address"
                    required>{{ Auth::user()->student->address }}</textarea>
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
                phone: {
                    required: true,
                    minlength: 10
                },
                email: {
                    required: true,
                },
                gender: {
                    required: true
                },
                age: {
                    required: true
                }
            },
            messages: {
                phone: {
                    required: "please enter valid phone number",
                    minlength: "Please enter at least 10 digit."
                },
                name: {
                    required: "please enter valid name"
                },
                email: {
                    required: "please enter valid email"
                },
                gender: {
                    required: "please select gender"
                },
                age: {
                    required: "please enter valid age"
                },
                address: {
                    required: "please select address"
                }
            },
            errorPlacement: function (error, element) {
                if (element.is(":radio")) {
                    error.appendTo(element.parent().parent());
                } else {
                    error.insertAfter(element);
                }
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
