<nav class="navbar navbar-expand-lg navbar-light bg-light p-3 custom-navbar">
    @if (Auth::user()->role == 'admin')
        <a class="navbar-brand" href="{{ route('admin.dashboard') }}">Admin System</a>
    @else
        <a class="navbar-brand" href="{{ route('student.dashboard') }}">Student Management System</a>
    @endif
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavDropdown"
        aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNavDropdown">
        <ul class="navbar-nav">
            @if (Auth::user()->role == 'admin')
                <li class="nav-item active">
                    <a class="nav-link" href="{{ route('students.index') }}">All Students</a>
                </li>
            @endif
            <li class="nav-item">
                @if (Auth::user()->role == 'admin')
                    <a class="nav-link" href="{{ route('admin.courses') }}">All Courses</a>
                @else
                    <a class="nav-link" href="{{ route('student.course') }}">All Courses</a>
                @endif
            </li>
            <li class="nav-item">
                <div class="dropdown">
                    <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton1"
                        data-bs-toggle="dropdown" aria-expanded="false">
                        My Profile
                    </button>
                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                        <li>
                            @if (Auth::user()->role == 'admin')
                                <a class="dropdown-item" href="{{ route('admin.profile') }}">Profile</a>
                            @else
                                <a class="dropdown-item" href="{{ route('student.profile') }}">Profile</a>
                            @endif
                        </li>
                        <li>
                            <form method="POST" action="{{ route('logout.user') }}" style="display: none;" id="logout-form">
                                @csrf
                                <button type="submit">Logout</button>
                            </form>
                            <a href="#" class="dropdown-item"
                                onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                Logout
                            </a>
                        </li>
                    </ul>
                </div>
            </li>
        </ul>
    </div>
</nav>
