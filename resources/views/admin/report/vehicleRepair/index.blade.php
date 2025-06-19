@extends('admin.template.template')
@section('title', 'Pengajuan Perbaikan Kendaraaan')
@section('content')

@push('css')
<style>
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
            <div class="col-sm-6"><h3 class="mb-0">Data Pengajuan Perbaikan Kendaraaan</h3></div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                    <li class="breadcrumb-item"><a href="{{url('/dashboard')}}">Dashboard</a></li>
                    <li class="breadcrumb-item">Laporan</li>
                    <li class="breadcrumb-item active" aria-current="page">Pengajuan Perbaikan Kendaraan</li>
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
                    <div class="card-header"><h3 class="card-title">Pengajuan Perbaikan Kendaraaan</h3></div>
                    <div class="col-12 d-flex">
                        @if(auth()->user()->hasRole(['admin', 'supervisor']))
                        <a href="{{ url('report/vehicleRepair/create') }}" class="btn btn-primary ms-3 mt-3">
                            Tambah
                        </a>
                        <a href="{{ url('report/vehicleRepair/invoice') }}" class="btn btn-warning ms-3 mt-3">
                            Invoice
                        </a>
                        @endif

                    </div>
                    <div class="card-body">
                        <table id="dataTableVehicleRepair" class="table table-striped responsive-table" style="width:100%">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Tanggal Pengajuan</th>
                                    <th>Kendaraan</th>
                                    <th>Dilaporkan Oleh</th>
                                    <th>Deksripsi</th>
                                    <th>Estimasi Biaya</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($vehicleRepair as $v)
                                <tr>
                                    <td data-label="No">{{ $loop->iteration  }}</td>
                                    <td data-label="Tanggal Pengajuan" class="text-start">{{ $v->submission_date  }}</td>
                                    <td data-label="Kendaraan">{{ $v->vehicle->name  }}</td>
                                    <td data-label="Dilaporkan Oleh">{{ $v->user?->admin->name ?? $v->user?->supervisor?->name ?? $v->user?->employee?->name ?? 'No PIC' }}</td>
                                    <td data-label="Deksripsi">{{ $v->description  ?? 'tidak ada deskripsi'}}</td>
                                    <td data-label="Estimasi Biaya" class="text-start">{{ $v->estimated_cost  }}</td>
                                    <td data-label="Aksi">
                                        @if(auth()->user()->hasRole(['admin', 'supervisor']))
                                        <a href="{{ url('/report/vehicleRepair/edit/' . $v->vehicleRepId) }}" class="btn btn-primary">Edit</a>                                       
                                        <a href="{{ url('/report/vehicleRepair/detail/' . $v->vehicleRepId) }}" class="btn btn-primary">Detail</a>                                       
                                        <form id="delete-form-{{ $v->vehicleRepId }}" action="{{ url('report/vehicleRepair/delete', $v->vehicleRepId) }}" method="POST" style="display: inline;">
                                                @csrf
                                                @method('POST')
                                                <button type="button" class="btn btn-danger btn-delete" data-id="{{ $v->vehicleRepId }}">
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
        new DataTable('#dataTableVehicleRepair');
        document.querySelector('#dataTableVehicleRepair tbody').addEventListener('click', function(event) {
            if(event.target.classList.contains('btn-delete')) {
                const id = event.target.getAttribute('data-id');
                
                Swal.fire({
                    title: 'Apakah kamu yakin?',
                    text: "Data Pengajuan Perbaikan kendaraan akan dihapus permanen!",
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