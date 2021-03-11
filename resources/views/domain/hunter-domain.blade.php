<div class="container bg-light border py-2 mb-4">
    <div class="row">
        <h3>Busqueda de emails en el dominio</h3>
        @if (session('domain-count'))
            <p class="badge bg-success">{{ session('domain-count') }}</p>
        @endif
        @if (session('hunter-delete-message'))
            <p class="badge bg-success">{{ session('hunter-delete-message') }}</p>
        @endif
        @if (session('hunter-asociate-message') === 'success')
            <p class="badge bg-success">Email asociado con exito.</p>
        @endif
        @if (session('hunter-asociate-message') === 'fail')
            <p class="badge bg-danger">Email ya asociado.</p>
        @endif
        <div class="table-responsive">
            <table id="hunter-domain-results" class="table border bg-white mt-2">
                <thead class="bg-green-soft">
                    <tr>
                        <th>Email</th>
                        <th>Datos personales</th>
                        <th>Verif</th>
                        <th>Prob</th>
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
                                                    <a class="text-dark text-decoration-none"
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
                                    <button class="btn btn-sm btn-primary" type="submit">Asociar</button>
                                </form>
                                <form action="/delete-hunter-data/{{ $data->id }}" method="post">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-danger" type="submit">Borrar</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <form action="/domain-search/{{ $domain->id }}" method="post">
                @csrf
                <button class="btn bg-green-soft my-2" type="submit">Actualizar resultados</button>
            </form>
        </div>
    </div>
</div>
