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
            </div>
        </div>
    </div>

    <div class="container-fluid bg-light py-4 mb-4">
        <div class="container">
            <div class="row">
                <h3>Busqueda de emails en el dominio</h3>
                @if (session('domain-count'))
                    <p class="text-success">{{ session('domain-count') }}</p>
                @endif
                @if (session('hunter-delete-message'))
                    <p class="text-success">{{ session('hunter-delete-message') }}</p>
                @endif
                @if (session('hunter-asociate-message') === 'success')
                    <p class="text-success">Email asociado con exito.</p>
                @endif
                @if (session('hunter-asociate-message') === 'fail')
                    <p class="text-danger">Email ya asociado.</p>
                @endif
                <div class="table-responsive">
                    <table id="hunter-domain-results" class="table table-light table-striped table-bordered">
                        <thead class="thead-light">
                            <tr>
                                <th>Email</th>
                                <th>Datos personales</th>
                                <th>Verificado</th>
                                <th>Probabilidad</th>
                                <th>Fuentes</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($hunterData as $data)
                                <tr>
                                    <td>{{ $data->email }}</td>
                                    <td>{{ !$data->first_name && !$data->last_name ? 'Sin datos' : "$data->first_name $data->last_name" }}
                                    </td>
                                    <td>{{ $data->verified ? $data->verified : 'No' }}</td>
                                    <td class="{{ $data->confidence > 70 ? 'text-success' : 'text-warning' }}">
                                        {{ $data->confidence }}%</td>
                                    <td>
                                        <div style="height: 50px; overflow-y: scroll;">
                                            <ul>
                                                @if (json_decode($data->sources) !== [])
                                                    @foreach (json_decode($data->sources) as $source)
                                                        <li>
                                                            <a class="text-decoration-none"
                                                                href="{{ $source }}">{{ $source }}</a>
                                                        </li>
                                                    @endforeach
                                                @else
                                                    <p class="text-warning">Sin fuentes</p>
                                                @endif
                                            </ul>
                                        </div>

                                    </td>
                                    <td class="d-flex justify-content-around">
                                        <form action="/asociate-hunter-data/{{ $data->id }}" method="post">
                                            @csrf
                                            <button class="btn btn-primary" type="submit">Asociar</button>
                                        </form>
                                        <form action="/delete-hunter-data/{{ $data->id }}" method="post">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-danger" type="submit">Borrar</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <form action="/domain-search/{{ $domain->id }}" method="post">
                        @csrf
                        <button class="btn btn-primary my-2" type="submit">Actualizar resultados</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid bg-light py-4 mb-4">
        <div class="container">
            <div class="row">
                <h4>Busqueda de email por datos personales</h4>
                @if (session('search-person'))
                    @if (session('search-person')[0] === 'success')
                        <p class="text-success">{{ session('search-person')[1] }}</p>
                    @else
                        <p class="text-danger">{{ session('search-person')[1] }}</p>
                    @endif
                @endif
                <form method="post" action="/person-search/{{ $domain->id }}">
                    <div class="row">
                        @csrf
                        <div class="col-12 col-sm-6 col-md-4">
                            <input class="form-control" type="text" name="first_name" id="first_name" placeholder="Nombre"
                                required>
                        </div>
                        <div class="col-12 col-sm-6 col-md-4">
                            <input class="form-control" type="text" name="last_name" id="last_name" placeholder="Apellido"
                                required>
                        </div>
                        <div class="col-12 col-sm-6 col-md-4">
                            <button class="btn btn-primary" type="submit">Buscar</button>
                        </div>
                    </div>
                </form>
                @if ($person)
                    <div class="table-responsive mt-4">
                        <h5>Ultima persona buscada</h5>
                        <table id="hunter-person-results" class="table table-light table-striped table-bordered">
                            <thead class="thead-light">
                                <tr>
                                    <th>Email</th>
                                    <th>Datos personales</th>
                                    <th>Verificado</th>
                                    <th>Probabilidad</th>
                                    <th>Fuentes</th>
                                    <th>Asociar mail</th>
                                </tr>
                            </thead>
                            <tbody>
                                <td>{{ $person ? $person->email : '-' }}</td>
                                <td>{{ ($person->first_name ? $person->first_name : '-' . ' ' . $person->last_name) ? $person->last_name : '' }}
                                </td>
                                <td>{{ $person->verified ? $person->verified : 'No' }}</td>
                                <td>{{ $person->confidence ? $person->confidence : '0' }}</td>
                                <td>
                                    <div style="height: 50px; overflow-y: scroll;">
                                        <ul>
                                            @if (json_decode($person->sources) !== [])
                                                @foreach (json_decode($person->sources) as $source)
                                                    <li>
                                                        {{ $source }}
                                                    </li>
                                                @endforeach
                                            @else
                                                <p class="text-warning">Sin fuentes</p>
                                            @endif
                                        </ul>
                                </td>
                                <td>
                                    <form action="/person-save/{{ $domain->id }}" method="post">
                                        @csrf
                                        <button class="btn btn-primary" type="submit">Asociar</button>
                                    </form>
                                </td>
                                @if (session('person-message'))
                                    <p class="text-danger">{{ session('person-message') }}</p>
                                @endif
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>

        </div>
    </div>

    <div class="container-fluid bg-light py-4" id="intelx">
        <div class="container">
            <div class="row">
                <h3>Resultados de inteligencia del dominio</h3>
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
                <form action="/intelxdomain/{{ $domain->id }}" method="post">
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
