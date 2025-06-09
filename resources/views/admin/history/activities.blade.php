@extends('admin.template.template')
@section('title', 'Riwayat Log Aktivitas')

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
            <div class="col-sm-6"><h3 class="mb-0">Riwayat Log Aktivitas</h3></div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                    <li class="breadcrumb-item"><a href="{{url('/dashboard')}}">Dashboard</a></li>
                    <li class="breadcrumb-item">Riwayat</li>
                    <li class="breadcrumb-item active" aria-current="page">Log Aktivitas</li>
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
                    <div class="card-header"><h3 class="card-title">Log Aktivitas</h3></div>
                    <div class="card-body">
                        <table id="dataTableNotification" class="table table-striped responsive-table" style="width:100%">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Waktu Aktivitas</th>
                                    <th>Oleh</th>
                                    <th>Aksi</th>
                                    <th>Deskripsi</th>
                                    <th>Ip Address</th>
                                    <th>Browser yang digunakan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($activities as $a)
                                <tr>
                                    <td data-label="No">{{ $loop->iteration  }}</td>
                                    <td data-label="Waktu Aktivitas">{{ $a->created_at->format('Y-m-d') }}</td>
                                    <td data-label="Oleh">{{ $a->user?->admin->name ?? $a->user?->supervisor?->name ?? $a->user?->employee?->name ?? 'No PIC' }}</td>
                                    <td data-label="Aksi">{{ $a->action_label  }}</td>
                                    <td data-label="Deskripsi">{{ $a->description  }}</td>
                                    <td data-label="Ip Address">{{ $a->ip_address  }}</td>
                                    <td data-label="Browser yang digunakan">{{ $a->user_agent  }}</td>
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
        new DataTable('#dataTableNotification');
    </script>
@endpush
@endsection