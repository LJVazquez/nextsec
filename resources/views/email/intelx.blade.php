<div class="container bg-light border py-2 mb-4" id="intelx">
    <div class="row">
        <h3>Resultados de inteligencia del email</h3>
        <p class="fw-lighter">Usando modalidad preview por limitaciones de la API free</p>
        @if (session('intelx-search-msg'))
            @if (session('intelx-search-msg')['status'] === 'success')
                <p class="badge bg-success">
                    {{ session('intelx-search-msg')['msg'] . session('intelx-search-msg')['props'] }}
                </p>
            @elseif ((session('intelx-search-msg')['status'] === 'fail'))
                <p class="badge bg-danger">{{ session('intelx-search-mgs')['msg'] }}</p>
            @endif
        @endif
        <div class="table-responsive">
            <table id="search-results" class="table border bg-white mt-2">
                <thead class="bg-green-soft">
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
                                        <button class="btn btn-sm btn-primary" type="submit">Descargar</button>
                                    </form>
                                @else
                                    <button class="btn btn-sm btn-primary disabled" type="button"> Premium</button>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <form action="/intelxmail/{{ $email->id }}" method="post">
            @csrf

            <button class="btn bg-green-soft " type="submit">Actualizar busqueda</button>
        </form>
    </div>
</div>
