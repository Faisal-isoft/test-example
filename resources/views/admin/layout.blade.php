<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin - Dashboard')</title>
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">
    <link rel="stylesheet" href="{{ asset('css/layout.css') }}">
</head>
<body>
    <div class="admin-container" >
        <div class="admin-header">
            <h1>@yield('header', 'Admin Dashboard')</h1>
            <div>
                <a href="{{ route('admin.add.product') }}" class="btn btn-primary">Add New Product</a>
                <a href="{{ route('logout') }}" class="btn btn-secondary">Logout</a>
            </div>
        </div>

        @if(session('success'))
            <div class="success-message">
                {{ session('success') }}
            </div>
        @endif

        <div class="admin-content">
            @yield('content')
        </div>
    </div>
</body>
</html>
