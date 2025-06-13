@extends('admin.template.template')
@section('title', 'Kategori')
@section('content')
@push('css')
    <style>
        .admin-photo {
            width: 100%;       
            height: auto;
            max-width: 150px;
            object-fit: cover;
            border-radius: 4px;
        }

        @media (max-width: 768px) {
            table.responsive-table thead {
                display: none;
            }

            table.responsive-table, 
            table.responsive-table tbody, 
            table.responsive-table tr, 
            table.responsive-table td {
                display: block;
                width: 100%;
            }

            table.responsive-table tr {
                margin-bottom: 1rem;
                border: 1px solid #dee2e6;
                border-radius: 5px;
                padding: 0.5rem;
                background-color: #f8f9fa;
            }

            table.responsive-table td {
                text-align: left;
                padding-left: 1rem;
                padding-right: 1rem;
                position: relative;
                font-size: 14px;
            }

            table.responsive-table td::before {
                content: attr(data-label);
                font-weight: bold;
                display: block;
                margin-bottom: 0.25rem;
            }

            .btn {
                margin: 0.25rem 0;
                font-size: 14px;
                padding: 0.375rem 0.75rem;
            }
        }
    </style>
@endpush

<div class="app-content-header">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6"><h3 class="mb-0">Data Kategori</h3></div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                    <li class="breadcrumb-item"><a href="{{url('/dashboard')}}">Dashboard</a></li>
                    <li class="breadcrumb-item">Setting</li>
                    <li class="breadcrumb-item active" aria-current="page">kategori</li>
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
                    <div class="card-header"><h3 class="card-title">Kategori</h3></div>
                    <div class="col-12 d-flex">
                        @if(auth()->user()->hasRole(['admin', 'supervisor']))
                        <a href="{{ url('setting/category/create') }}" class="btn btn-primary ms-3 mt-3">
                            Tambah
                        </a>
                        <a href="{{ url('setting/category/invoice') }}" class="btn btn-warning ms-3 mt-3">
                            Invoice
                        </a>
                        @endif

                    </div>
                    <div class="card-body">
                        <table id="dataTableCategory" class="table table-striped responsive-table" style="width:100%">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Kategori</th>
                                    <th>Tipe Kendaraan</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($category as $cat)
                                <tr>
                                    <td data-label="No">{{ $loop->iteration  }}</td>
                                    <td data-label="Nama Kategori">{{ $cat->name  }}</td>
                                    <td data-label="Tipe Kendaraan">{{ $cat->type_label  }}</td>
                                    <td data-label="Aksi">
                                        @if(auth()->user()->hasRole(['admin', 'supervisor']))
                                            <a href="{{ url('/setting/category/edit/' . $cat->categoryId) }}" class="btn btn-primary">Edit</a>                                       
                                            <form id="delete-form-{{ $cat->categoryId }}" action="{{ url('setting/category/delete', $cat->categoryId) }}" method="POST" style="display: inline;">
                                                @csrf
                                                @method('POST')
                                                <button type="button" class="btn btn-danger btn-delete" data-id="{{ $cat->categoryId }}">
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
    <script>
        new DataTable('#dataTableCategory');
        document.querySelector('#dataTableCategory tbody').addEventListener('click', function(event) {
            if(event.target.classList.contains('btn-delete')) {
                const id = event.target.getAttribute('data-id');

                Swal.fire({
                    title: 'Apakah kamu yakin?',
                    text: "Data kategori akan dihapus permanen!",
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