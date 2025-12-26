<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.7.1.js" type="text/javascript"></script>
    <script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('js/jquery.validate.min.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
    <title>Tasks</title>
</head>
<style>
    .container {
        max-width: 500px;
    }
</style>

<body>
    <div class="container mt-5">
        @if (session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif
        <h1>Login</h1>
        <form method="POST" id="LoginFormData" action="{{ route('login.form') }}">
            @csrf
            <div class="mb-3 mt-3">
                <label for="email" class="form-label">Email:</label>
                <input type="email" class="form-control" id="email" value="{{ old('email') }}" placeholder="Enter email"
                    name="email">
                @error('email')
                    <div class="text-danger">
                        {{ $message }}
                    </div>
                @enderror
            </div>
            <div class="mb-3">
                <label for="pwd" class="form-label">Password:</label>
                <input type="password" class="form-control" id="pwd" placeholder="Enter password" name="password">
                @error('password')
                    <div class="text-danger">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <button type="submit" class="btn btn-primary">Login</button>
        </form>
    </div>
    <script>
        $("#LoginFormData").validate({
            rules: {
                email: {
                    required: true,
                },
                password: {
                    required: true,
                },
            },
            messages: {
                email: {
                    required: "Please Enter Valid Email"
                },
                password: {
                    required: "Please Enter Valid Password",
                },
            }
        });
    </script>
</body>

</html>
