@extends('admin.template.template')
@section('title', 'Laporan Mingguan')

@section('content')
@push('css')
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
            <div class="col-sm-6"><h3 class="mb-0">Data Laporan Mingguan</h3></div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                    <li class="breadcrumb-item"><a href="{{url('/dashboard')}}">Dashboard</a></li>
                    <li class="breadcrumb-item">Laporan</li>
                    <li class="breadcrumb-item active" aria-current="page">Laporan Mingguan</li>
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
                    <div class="card-header"><h3 class="card-title">Laporan Mingguan</h3></div>
                    <div class="col-12 d-flex">
                        @if(auth()->user()->hasRole(['admin', 'supervisor', 'petugas']))
                        <a href="{{ url('report/weeklyReport/create') }}" class="btn btn-success ms-3 mt-3">
                            Tambah
                        </a>
                        @endif
                        @if(auth()->user()->hasRole(['admin', 'supervisor']))
                        <a href="{{ url('report/weeklyReport/invoice') }}" class="btn btn-secondary ms-3 mt-3">
                            Invoice
                        </a>
                        @endif

                    </div>
                    <div class="card-body">
                        <table id="dataTableWeeklyReport" class="table table-striped responsive-table" style="width:100%">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Tanggal Laporan</th>
                                    <th>Petugas</th>
                                    <th>Kendaraan</th>
                                    <th>Catatan</th>
                                    <th>Status Laporan</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($weeklyReport as $week)
                                <tr>
                                    <td data-label="No">{{ $loop->iteration  }}</td>
                                    <td data-label="Tanggal Laporan">{{ $week->report_date  }}</td>
                                    <td data-label="Petugas">{{ $week->user?->admin->name ?? $week->user?->supervisor?->name ?? $week->user?->employee?->name ?? 'No PIC' }}</td>
                                    <td data-label="Kendaraan">{{ $week->vehicle->name  }}</td>
                                    <td data-label="Catatan">{{ $week->note  }}</td>
                                    <td data-label="Status Laporan">
                                        {{ $week->status_label  }}
                                         <i class="bi bi-question-circle-fill text-primary ms-2" 
                                            data-bs-toggle="tooltip" 
                                            data-bs-placement="top" 
                                            title="{{($week->status_description) }}">
                                        </i>
                                    </td>
                                    <td data-label="Aksi">
                                        @if(auth()->user()->hasRole(['admin', 'supervisor', 'petugas']))
                                            <a href="{{ url('/report/weeklyReport/edit/' . $week->weekReportId) }}" class="btn btn-primary">Edit</a>  
                                            @if($week->status == \App\Models\Report\WeeklyReport\WeeklyReport::STATUS_PENDING)
                                                <a href="{{ url('report/weeklyReport/show/' . $week->weekReportId) }}" class="btn btn-warning">
                                                    Validasi Laporan
                                                </a>
                                            @elseif($week->status != \App\Models\Report\WeeklyReport\WeeklyReport::STATUS_PENDING)
                                                <a href="{{ url('report/weeklyReport/show/' . $week->weekReportId) }}" class="btn btn-info">
                                                    Lihat Detail
                                                </a>
                                            @endif
                                            <form id="delete-form-{{ $week->weekReportId }}" action="{{ url('report/weeklyReport/delete', $week->weekReportId) }}" method="POST" style="display: inline;">
                                                @csrf
                                                @method('POST')
                                                <button type="button" class="btn btn-danger btn-delete" data-id="{{ $week->weekReportId }}">
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
        new DataTable('#dataTableWeeklyReport');
        const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]')
        tooltipTriggerList.forEach(tooltipTriggerEl => {
            new bootstrap.Tooltip(tooltipTriggerEl)
        });
        document.querySelector('#dataTableWeeklyReport tbody').addEventListener('click', function(event) {
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