@extends('admin.template.template')
@section('title', 'Riwayat Notifikasi')
@section('content')
@push('css')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/2.2.1/css/dataTables.bootstrap5.css">
<style>
.vehicle-photo {
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
            <div class="col-sm-6"><h3 class="mb-0">Riwayat Notifikasi</h3></div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                    <li class="breadcrumb-item"><a href="{{url('/dashboard')}}">Dashboard</a></li>
                    <li class="breadcrumb-item">Riwayat</li>
                    <li class="breadcrumb-item active" aria-current="page">Notifikasi</li>
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
                    <div class="card-header"><h3 class="card-title">Notifikasi</h3></div>
                    <div class="card-body">
                        <table id="dataTableNotification" class="table table-striped responsive-table" style="width:100%">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Tanggal</th>
                                    <th>Ditujukan untuk</th>
                                    <th>Judul Notifikasi</th>
                                    <th>Pesan</th>
                                    <th>Status Dibaca</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($notification as $not)
                                <tr>
                                    <td data-label="No">{{ $loop->iteration  }}</td>
                                    <td data-label="Tanggal">{{ $not->created_at->format('Y-m-d') }}</td>
                                    <td data-label="Ditujukan Untuk">{{ $not->user?->admin->name ?? $not->user?->supervisor?->name ?? $not->user?->employee?->name ?? 'No PIC' }}</td>
                                    <td data-label="Judul Notifikasi">{{ $not->title  }}</td>
                                    <td data-label="Pesan">{{ $not->message  }}</td>
                                    <td data-label="Status Dibaca">
                                        @if ($not->is_read)
                                            <span class="badge bg-success">Sudah Dibaca</span>
                                        @else
                                            <span class="badge bg-danger">Belum Dibaca</span>
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
        new DataTable('#dataTableNotification');
    </script>
@endpush
@endsection