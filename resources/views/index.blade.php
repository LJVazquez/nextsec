@extends ('layout')

@section('content')

    <div class="container">
        <div class="row ">
            <div class="col-12 ">
                @if (Auth::check())
                    <a href="/users" class="btn btn-primary">Usuarios</a>
                    <a href="/domains" class="btn btn-secondary">Dominios</a>
                    <a href="/emails" class="btn btn-info">Emails</a>
                    <form action="/logout" method="POST" class="mt-1">
                        @csrf
                        <button class="btn btn-danger" type="submit">Logout</button>
                    </form>
                @else
                    <a href="/login" class="btn btn-success">Login</a>
                    <a href="/register" class="btn btn-success">Registrarse</a>
                @endif
            </div>
        </div>
    </div>


@endsection
