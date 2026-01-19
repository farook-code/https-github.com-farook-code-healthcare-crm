<!DOCTYPE html>
<html lang="en">
<head>
    <title>Healthcare CRM</title>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    {{-- Bootstrap --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

@php
    $role = auth()->user()->role->slug ?? null;
@endphp

{{-- TOP NAVBAR --}}
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">Healthcare CRM</a>

        <div class="collapse navbar-collapse">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">

                @if($role === 'admin')
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('admin.dashboard') }}">Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('admin.users.index') }}">Users</a>
                    </li>
                @endif

                @if($role === 'doctor')
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('doctor.dashboard') }}">Doctor</a>
                    </li>
                @endif

                @if($role === 'nurse')
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('nurse.dashboard') }}">Nurse</a>
                    </li>
                @endif

                @if($role === 'reception')
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('reception.dashboard') }}">Reception</a>
                    </li>
                @endif

                @if($role === 'patient')
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('patient.dashboard') }}">My Account</a>
                    </li>
                @endif

            </ul>

            <span class="navbar-text text-white me-3">
                {{ auth()->user()->name }}
            </span>

            <a class="btn btn-outline-light btn-sm"
               href="{{ route('logout') }}"
               onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                Logout
            </a>
        </div>
    </div>
</nav>

{{-- PAGE CONTENT --}}
<div class="container-fluid mt-4">
    @yield('content')
</div>

<form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
    @csrf
</form>

</body>
</html>
