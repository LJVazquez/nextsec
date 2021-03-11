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
                <h1 class="display-4">{{ $email->name }}</h2>
            </div>
        </div>
    </div>

    <div class="container">
        @include('email.intelx')
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
