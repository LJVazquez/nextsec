<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-BmbxuPwQa2lc/FVzBcNJ7UAyJxM6wuqIj61tLrc4wSX0szH/Ev+nYRRuWlolflfl" crossorigin="anonymous">
    @yield('css')
    <style>
        .bg-green {
            background-color: #08FFBE;
        }

    </style>

    <title>NextSect</title>
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-white mb-2">
        <div class="container">
            <a class="navbar-brand" href="/"><img src="{{ asset('images/lock-solid.svg') }}" width="30" height="30"
                    class="d-inline-block align-top" alt="">
                NextSecApp
            </a>
            <ul class="navbar-nav mr-auto">
                @if (Auth::check())
                    <div class="dropdown">
                        <button class="btn" type="button" id="menu" data-bs-toggle="dropdown" aria-expanded="false">
                            {{ Auth::user()->name }}
                        </button>
                        <ul class="dropdown-menu" aria-labelledby="menu">
                            <li><a class="dropdown-item" href="/user/profile">Perfil</a></li>
                            <li>
                                <form action="/logout" method="POST">
                                    @csrf
                                    <button class="btn btn-link dropdown-item" type="submit">Logout</button>
                                </form>
                            </li>
                        </ul>
                    </div>
                @else
                    <li class="nav-item active">
                        <a class="nav-link" href="/login">Login</a>
                    </li>
                    <li class="nav-item active">
                        <a class="nav-link" href="/register">Registrarse</a>
                    </li>
                @endif
            </ul>
        </div>

    </nav>


    <div class="container-fluid">
        @yield('content')
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-b5kHyXgcpbZJO/tY9Ul7kGkf1S0CWuKcCD38l8YkeH8z8QjE0GmW1gYU5S9FOnJ0" crossorigin="anonymous">
    </script>

    @yield('js')

</body>

</html>
