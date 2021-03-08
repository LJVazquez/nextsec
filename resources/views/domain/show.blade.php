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
                <h1 class="text-info">{{ $domain->name }}</h2>
                    <p class="text-secondary">Registered by {{ $domain->user->name }}</p>
            </div>
        </div>
    </div>

    <div class="container-fluid bg-light py-4 mb-4">
        <div class="container">
            <div class="row">
                <h3>Emails asociados al dominio</h3>
                <div class="table-responsive">
                    <table id="hunter-domain-results" class="table table-light table-striped table-bordered">
                        <thead class="thead-light">
                            <tr>
                                <th>Email</th>
                                <th>Datos personales</th>
                                <th>Tipo</th>
                                <th>Fuentes</th>
                                <th>Agregar email</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($hunterdomaindata as $data)
                                <tr>
                                    <td>{{ $data->email }}</td>
                                    <td>{{ !$data->first_name && !$data->last_name ? 'Sin datos' : "$data->first_name $data->last_name" }}
                                    </td>
                                    <td>{{ $data->type }}</td>
                                    <td>
                                        <div style="height: 50px; overflow-y: scroll;">
                                            <ul>
                                                @foreach (explode('|', $data->sources) as $source)
                                                    <li>
                                                        {{ $source }}
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    </td>
                                    <td><button class="btn btn-primary" type="button">Agregar</button></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <form action="/domain-search/{{ $domain->id }}" method="get">
                        @csrf
                        <button class="btn btn-primary my-2" type="submit">Actualizar resultados</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid bg-light py-4" id="intelx">
        <div class="container">
            <div class="row">
                <h3>Resultados de inteligencia del dominio</h3>
                <p class="fw-lighter">Usando modalidad preview por limitaciones de la API free</p>
                @if (session('count'))
                    <p class="text-success">{{ session('count') }} resultados nuevos.</p>
                @endif
                <div class="table-responsive">
                    <table id="intelx-results" class="table table-light table-striped table-bordered">
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
                </div>
                <form action="/domains/{{ $domain->id }}" method="post">
                    @csrf
                    <button class="btn btn-primary my-2" type="submit">Actualizar resultados</button>
                </form>
            </div>
        </div>
    </div>

    <div class="container mt-5" id="asociated-emails">
        <h3>Emails asociados al dominio</h3>
        <ul class="list-group">
            @foreach ($emails as $email)
                <li class="list-group-item list-group-item-primary">
                    <a href="/emails/{{ $email->id }}" style="text-decoration: none">{{ $email->name }}</a>
                </li>
            @endforeach
        </ul>
    </div>

    <!-- añadir a index !-->

    {{-- <div class="container">
        @can('delete', $domain)
            <form method="post" action="/domains/{{ $domain->id }}">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger mt-1">Borrar dominio</button>
            </form>
        @endcan
    </div> --}}


@endsection

@section('js')
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="https://cdn.datatables.net/1.10.23/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.23/js/dataTables.bootstrap5.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#intelx-results').DataTable({
                "lengthMenu": [5, 10, 20, 50],
            });
        });
        $(document).ready(function() {
            $('#hunter-domain-results').DataTable({
                "lengthMenu": [5, 10, 20, 50]
            });
        });

    </script>
@endsection
