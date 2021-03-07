@extends('layout')

@section('css')
    <link rel="stylesheet"
        href="https://cdn.datatables.net/1.10.23/css/dataTables.bootstrap5.min.css
                                                                                                                                                                        ">
@endsection

@section('content')

    <div class="container">
        <h1 class="text-primary">Email</h1>
        <h2 class="text-info">{{ "$email->name@$domain->name" }}</h2>
        <p class="text-secondary">Registered by {{ $user->name }}</p>
    </div>
    <div class="container">
        <form action="/emails/{{ $email->id }}" method="post">
            @csrf
            <button class="btn btn-primary" type="submit">Buscar</button>
        </form>
    </div>
    <div class="container" id="results">


        <div class="row">
            <div class="col-12">
                <h3>Resultados de la busqueda</h3>
                @if (session('count'))
                    <p class="text-success">{{ session('count') }} resultados nuevos.</p>
                @endif
                <div class="table-responsive">
                    <table id="search-results" class="table table-light table-striped table-bordered">
                        <thead class="thead-light">
                            <tr>
                                <th>Nombre</th>
                                <th>Actualizado</th>
                                <th>Bucket</th>
                                <th>Archivo</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($intelxdata as $result)
                                <tr>
                                    <td>{{ $result->name ? $result->name : 'Unnamed' }}</td>
                                    <td>{{ $result->updated_at }}</td>
                                    <td>{{ $result->bucket }}</td>
                                    <td>
                                        @if (strpos($result->bucket, 'private') === false)
                                            <form action="/getFile/{{ $result->id }}" method="get">
                                                @csrf
                                                <button class="btn btn-primary" type="submit">Descargar</button>
                                            </form>
                                        @else
                                            <button class="btn btn-primary disabled" type="button">Solo Premium</button>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    @can('delete', $email)
                        <form method="post" action="/emails/{{ $email->id }}">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger mt-1">Borrar email</button>
                        </form>
                    @endcan
                </div>
            </div>
        </div>
    </div>

@endsection

@section('js')
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="https://cdn.datatables.net/1.10.23/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.23/js/dataTables.bootstrap5.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#search-results').DataTable({
                "lengthMenu": [5, 10, 20, 50]
            });
        });

    </script>
@endsection
