{{-- resources/views/partials/header.blade.php --}}

<header class="fixed-header">
    <div class="container-fluid">
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
            <a class="navbar-brand" href="{{ url('/') }}">
                <img src="{{ asset('path/to/your/ideas_logo.png') }}" alt="Ideas Logo" height="30"> Ideas
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
                <ul class="navbar-nav">
                    @guest {{-- If user is NOT logged in --}}
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">Login</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('register') }}">Register</a>
                        </li>
                    @else {{-- If user IS logged in --}}
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('dashboard') }}">Profile</a> {{-- Or whatever your profile page is --}}
                        </li>
                        <li class="nav-item">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="nav-link btn btn-link text-white-50">Logout</button>
                            </form>
                        </li>
                    @endguest
                </ul>
            </div>
        </nav>
    </div>
</header>
