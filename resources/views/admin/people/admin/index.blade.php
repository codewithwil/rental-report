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
            <div class="col-sm-6"><h3 class="mb-0">Informasi Admin</h3></div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                    <li class="breadcrumb-item"><a href="{{url('/dashboard')}}">Dashboard</a></li>
                    <li class="breadcrumb-item">Konfigurasi</li>
                    <li class="breadcrumb-item active" aria-current="page">admin</li>
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
                    <div class="col-12 d-flex">
                        @if(auth()->user()->hasRole(['admin', 'supervisor']))
                        <a href="{{ url('people/admin/create') }}" class="btn btn-primary ms-3 mt-3">
                            Tambah
                        </a>
                        <a href="{{ url('people/admin/invoice') }}" class="btn btn-warning ms-3 mt-3">
                            Invoice
                        </a>
                        @endif


                    </div>
                    
                    <div class="card-body">
                        <table id="dataTableUsers" class="table table-striped" style="width:100%">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Foto</th>
                                    <th>Nama</th>
                                    <th>Email</th>
                                    <th>Nomor Telepon</th>
                                    <th>Level</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($users as $us)
                                <tr>
                                    <td>{{ $loop->iteration  }}</td>
                                    <td>
                                        @if($us->foto)
                                        <img src="{{ asset('storage/' . $us->foto) }}" alt="Foto" 
                                        class="rounded-circle" 
                                        style="width: 100px; height: 100px; object-fit: cover;">
                                   
                                        @else
                                            <span class="text-muted">Tidak ada foto</span>
                                        @endif
                                    </td>                                    
                                    <td>{{ $us->name  }}</td>
                                    <td>{{ $us->user->email }}</td>
                                    <td>{{ $us->telepon ?? 'phone not set' }}</td>
                                    <td>
                                        @foreach ($us->user->roles as $role)
                                            {{ $role->name }}
                                        @endforeach
                                    </td>
                                    <td>
                                        @if(auth()->user()->hasRole(['admin', 'supervisor']))
                                        <a href="{{ url('/people/admin/edit/' . $us->adminId) }}" class="btn btn-primary">Edit</a>        
                                        @endif
                                        @if(auth()->user()->hasRole(['admin', 'supervisor']) && auth()->user()->id !== $us->user_id) 
                                           <form id="delete-form-{{ $us->adminId }}" action="{{ url('people/admin/delete', $us->adminId) }}" method="POST" style="display: inline;">
                                                @csrf
                                                @method('POST')
                                                <button type="button" class="btn btn-danger btn-delete" data-id="{{ $us->adminId }}">
                                                    Hapus
                                                </button>
                                            </form>
                                        @endif
                                        
                                    </td>
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
        document.querySelector('#dataTableUsers tbody').addEventListener('click', function(event) {
            if(event.target.classList.contains('btn-delete')) {
                const id = event.target.getAttribute('data-id');

                Swal.fire({
                    title: 'Apakah kamu yakin?',
                    text: "Data user akan dihapus permanen!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Ya, hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        document.getElementById('delete-form-' + id).submit();
                    }
                });
            }
        });
    </script>
@endpush
@endsection