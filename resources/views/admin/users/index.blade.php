@extends('admin.template.template')
@section('title', 'Users')

@section('content')
@push('css')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/2.2.1/css/dataTables.bootstrap5.css">
@endpush

<div class="app-content-header">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6"><h3 class="mb-0">Informasi User</h3></div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                    <li class="breadcrumb-item"><a href="/dashboard">Dashboard</a></li>
                    <li class="breadcrumb-item">Konfigurasi</li>
                    <li class="breadcrumb-item active" aria-current="page">Users</li>
                </ol>
            </div>
        </div>
    </div>
</div>
<div class="app-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card mb-4">
                    <div class="card-header"><h3 class="card-title">Users</h3></div>
                    <div class="card-body">
                        <table id="dataTableUsers" class="table table-striped" style="width:100%">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama</th>
                                    <th>Email</th>
                                    <th>Level</th>
                                    {{-- @if(auth()->user()->hasRole(['admin', 'supervisor']))
                                    <th>Aksi</th>
                                    @endif --}}
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($users as $us)
                                <tr>
                                    <td>{{ $loop->iteration  }}</td>
                                    <td>{{ $us->name  }}</td>
                                    <td>{{ $us->email }}</td>
                                    <td>
                                        @foreach ($us->roles as $role)
                                            {{ $role->name }}
                                        @endforeach
                                    </td>
                                    {{-- <td>
                                        @if(auth()->user()->hasRole(['admin', 'supervisor']))
                                        <a href="{{ url('/people/users/edit/' . $us->id) }}" class="btn btn-primary">Edit</a>        
                                        @endif
                                        @if(auth()->user()->hasRole(['admin']))                           
                                        <form action="{{ url('people/users/delete', $us->id) }}" method="POST" style="display: inline;">
                                            @csrf
                                            @method('POST') 
                                            <button type="submit" class="btn btn-danger text-light hover:text-red-700" onclick="return confirm('Are you sure?')">Hapus</button>
                                        </form>
                                        @endif
                                    </td> --}}
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
    </div>
</div>


@push('js')
    <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/2.2.1/js/dataTables.js"></script>
    <script src="https://cdn.datatables.net/2.2.1/js/dataTables.bootstrap5.js"></script>
    <script>
        new DataTable('#dataTableUsers');
    </script>
@endpush
@endsection