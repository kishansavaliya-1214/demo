@extends('layouts.app')
@section('content')
    <div class="container mt-2">
        <h1>Update Student</h1>
        <form action="{{ route('students.update', $student->id) }}" id="FormEmployeeData" method="post"
            enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="form-group my-2">
                <label for="Name" class="form-label">Name</label>
                <input type="text" class="form-control" id="name" name="name" value="{{ $student->user->name ?? null }}"
                    placeholder="Enter name" required />
                @error('name')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group my-2">
                <label for="Email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email" placeholder="Enter email"
                    value="{{ $student->user->email ?? null }}" required />
                @error('email')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group my-2">
                <label for="photo" class="form-label">Photo</label>
                <input type="file" class="form-control" id="photo"
                    onchange="document.getElementById('imagePreview').src=window.URL.createObjectURL(this.files[0])"
                    name="photo" />
                @if ($student->photo)
                    <img src="{{ asset('images/' . $student->photo) }}" id="imagePreview" class="rounded mt-2" width="100"
                        height="100" alt="">
                @else
                    <img src="{{ asset('images/Noimage.png') }}" id="imagePreview" height="100" width="100" class="rounded mt-2"
                        alt="">
                @endif
                @error('photo')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group my-2">
                <label for="phone" class="form-label">Phone</label>
                <input type="number" class="form-control" id="phone" minlength="10" value="{{ $student->phone }}"
                    name="phone" placeholder="Enter phone" required />
                @error('phone')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group my-2">
                <label for="age" class="form-label">Age</label>
                <input type="number" class="form-control" id="age" name="age" value="{{ $student->age }}"
                    placeholder="Enter age" required />
                @error('age')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group my-2">
                <label for="gender" class="form-label">gender :</label>
                <input type="radio" class="form-check-input me-2" name="gender" id="gender" value="male" @if ($student->gender == "male") {{ 'checked' }} @endif><label for="">Male</label>
                <input type="radio" class="form-check-input me-2" name="gender" id="gender" value="female" @if ($student->gender == "female") {{ 'checked' }} @endif /><label for="">FeMale</label>
                <br />
                <label id="gender-error" class="error" style="display:none;" for="gender">please select gender</label>
                @error('gender')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group my-2">
                <label for="address" class="form-label">Address</label>
                <textarea name="address" id="address" class="form-control" placeholder="Enter Address"
                    required>{{ $student->address }}</textarea>
                @error('address')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group my-2">
                <input type="submit" value="Update Student" class="btn btn-primary" />
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
                },
                phone: {
                    required: true,
                    minlength: 10,
                    maxlength: 10,
                    digits: true
                },
                email: {
                    required: true,
                    email: true
                },
                photo: {
                    extension: "jpg|jpeg",
                    filesize: 2048
                },
                gender: {
                    required: true
                },
                age: {
                    required: true,
                    digits: true
                }
            },
            messages: {
                phone: {
                    required: "please enter valid phone number",
                    minlength: "Please enter at least 10 digit.",
                    maxlength: "Phone number must be exactly 10 digits.",
                    digits: "Phone number must contain only digits."
                },
                name: {
                    required: "please enter valid name"
                },
                email: {
                    required: "please enter valid email",
                    email: "Please enter a valid email address"
                },
                photo: {
                    required: "please choose valid image",
                    extension: "Only JPG, JPEG files are allowed",
                    filesize: "File size must be less than 2 MB."
                },
                gender: {
                    required: "please select gender"
                },
                age: {
                    required: "please enter valid age",
                    digits: "age must contain only digits"
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
@endpush
