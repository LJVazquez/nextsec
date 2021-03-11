<div class="container border bg-light py-2">
    <div class="row">
        <div class="col-12 d-flex align-items-center">
            <h4>Emails</h4>
            <a href="emails/create" class="btn bg-green-soft btn-sm mx-2">+ Agregar</a>
        </div>

        @if (session('email-update'))

            <div class="col-12">
                <p class="text-success">{{ session('email-update') }}</p>
            </div>

        @endif

        <div class="table-fluid">
            <table class="table table-bordered bg-white mt-2">
                <thead class="bg-green-soft">
                    <tr>
                        <th>ID</th>
                        <th>Email</th>
                        <th>Nombre</th>
                        <th>Apellido</th>
                        <th>Asociado a</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>

                    @foreach ($emails as $email)
                        <tr>
                            <td>{{ $email->id }}</td>
                            <td><a href="/emails/{{ $email->id }}" class="text-dark text-decoration-none">
                                    {{ $email->name }}
                                </a></td>
                            <td>{{ $email->first_name ? $email->first_name : 'n/a' }}</td>
                            <td>{{ $email->last_name ? $email->last_name : 'n/a' }}</td>
                            <td>{{ $email->domain_id ? $email->domain->name : 'n/a' }}</td>
                            <td class="d-flex">
                                @can('author', $email)
                                    <a href="/emails/{{ $email->id }}/edit"
                                        class="btn btn-sm btn-secondary me-2">Editar</a>
                                    <form action="/emails/{{ $email->id }}" method="post">
                                        @csrf
                                        @method('DELETE')
                                        <button onClick="return confirm('¿Eliminar {{ $email->name }}?')" type="submit"
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
