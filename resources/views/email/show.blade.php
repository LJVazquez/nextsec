@extends('layout')

@section('css')
    <link rel="stylesheet"
        href="https://cdn.datatables.net/1.10.23/css/dataTables.bootstrap5.min.css
                                                                                                                                                                                                                                                                                                                                    ">
@endsection

@section('content')

    <div class="container-fluid my-2" id="domain-info">
        <div class="container">
            <div class="row">
                <h1 class="text-info">{{ $email->name }}</h2>
            </div>
        </div>
    </div>

    <div class="container-fluid bg-light py-4" id="intelx">
        <div class="container">
            <div class="row">
                <h3>Resultados de inteligencia del email</h3>
                <p class="fw-lighter">Usando modalidad preview por limitaciones de la API free</p>
                @if (session('intelx-search-msg'))
                    @if (session('intelx-search-msg')['status'] === 'success')
                        <p class="text-success">
                            {{ session('intelx-search-msg')['msg'] . session('intelx-search-msg')['props'] }}
                        </p>
                    @elseif ((session('intelx-search-msg')['status'] === 'fail'))
                        <p class="text-danger">{{ session('intelx-search-mgs')['msg'] }}</p>
                    @endif
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
                                            <button class="btn btn-primary disabled" type="button">Solo
                                                Premium</button>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <form action="/intelxmail/{{ $email->id }}" method="post">
                    @csrf

                    <button class="btn btn-primary " type="submit">Actualizar busqueda</button>
                </form>
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
