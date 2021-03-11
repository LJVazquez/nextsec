<div class="container border bg-light py-2">
    <div class="row">
        <div class="col-12 d-flex align-items-center">
            <h4>Dominios</h4>
            <a href="domains/create" class="btn bg-green-soft btn-sm mx-2">+ Agregar</a>
        </div>

        @if (session('domain-update'))

            <div class="col-12">
                <p class="badge bg-success">{{ session('domain-update') }}</p>
            </div>

        @endif

        <div class="table-responsive">
            <table class="table table-bordered border bg-white mt-2">
                <thead class="bg-green-soft">
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>

                    @foreach ($domains as $domain)
                        <tr>
                            <td>{{ $domain->id }}</td>
                            <td><a href="/domains/{{ $domain->id }}" class="text-dark text-decoration-none">
                                    {{ $domain->name }}
                                </a></td>
                            <td class="d-flex">
                                @can('author', $domain)
                                    <a href="/domains/{{ $domain->id }}/edit"
                                        class="btn btn-sm btn-secondary me-2">Editar</a>
                                    <form action="/domains/{{ $domain->id }}" method="post">
                                        @csrf
                                        @method('DELETE')
                                        <button onClick="return confirm('¿Eliminar {{ $domain->name }}?')" type="submit"
                                            class="btn btn-sm btn-danger">Borrar</button>
                                    </form>
                                @endcan
                            </td>
                        </tr>
                    @endforeach

                </tbody>
            </table>
        </div>
    </div>
</div>
