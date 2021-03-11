@extends ('layout')

@section('content')

    @if (Auth::check())
        <div class="container my-2">
            @include('dash.domains-panel')
        </div>

        <div class="container">
            @include('dash.emails-panel')
        </div>

    @else
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <h1>Call to action para registrarse al servicio, etc</h1>
                </div>
            </div>
        </div>
    @endif


@endsection
