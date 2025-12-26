<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
    <script src="{{ asset('js/jquery.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="{{ asset('js/jquery.validate.min.js') }}"></script>
    <script src="{{ asset('js/additional-methods.min.js') }}"></script>
    <script src="{{ asset('js/sweetalert2.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
    <link href="{{ asset('css/dataTables.bootstrap5.css') }}">
    </link>
    <link rel="stylesheet" type="text/css" href="{{ asset('css/toastr.min.css') }}">
    <script src="{{ asset('js/dataTables.js') }}">  </script>
    <script src="{{ asset('js/dataTables.bootstrap5.js') }}"></script>
    <script src="{{ asset('js/toastr.min.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('css/font-awesome.min.css') }}">
    <title>Tasks</title>
</head>
<style>
    .error {
        color: red;
    }

    #photo-error {
        display: block;
    }
</style>
@stack('styles')

<body>
    <header>
        @include('layouts.header')
    </header>

    <div class="container">
        <div class="row">
            {{-- Main Content --}}
            <main class="col-md-12">
                @yield('content')
            </main>
        </div>
    </div>
    @stack('scripts')
    <header>
        @include('layouts.footer')
    </header>
</body>
</html>
