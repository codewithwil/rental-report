@extends('admin.template.template')
@section('title', 'Laporan Keuangan Kendaraan Rental')
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
            <div class="col-sm-6"><h3 class="mb-0">Laporan Keuangan Kendaraan Rental</h3></div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                    <li class="breadcrumb-item"><a href="{{url('/dashboard')}}">Dashboard</a></li>
                    <li class="breadcrumb-item">Laporan</li>
                    <li class="breadcrumb-item active" aria-current="page">Kas Kendaraan</li>
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
                    <div class="card-body">
                       <form method="GET" action="{{ url('report/kas/pdf') }}" target="_blank" class="mb-3 d-flex gap-2 align-items-end">
                            <div>
                                <label>Bulan</label>
                                <select name="month" class="form-control">
                                    <option value="">Semua</option>
                                    @for ($m = 1; $m <= 12; $m++)
                                        <option value="{{ $m }}">{{ DateTime::createFromFormat('!m', $m)->format('F') }}</option>
                                    @endfor
                                </select>
                            </div>
                            <div>
                                <label>Tahun</label>
                                <select name="year" class="form-control">
                                    <option value="">Semua</option>
                                    @for ($y = now()->year; $y >= 2020; $y--)
                                        <option value="{{ $y }}">{{ $y }}</option>
                                    @endfor
                                </select>
                            </div>
                            <div>
                                <button class="btn btn-primary" type="submit">
                                    <i class="fas fa-file-pdf"></i> Cetak PDF
                                </button>
                            </div>
                        </form>
                        <table id="dataTableKas" class="table table-bordered table-hover responsive-table" style="width:100%">
                            <thead class="table-light">
                                <tr>
                                    <th style="width: 5%;">No</th>
                                    <th>Sumber</th>
                                    <th>Tanggal</th>
                                    <th>Jenis</th>
                                    <th class="text-end">Jumlah</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($kas as $k)
                                    @php
                                        $source = class_basename($k->payable_type);
                                        $sourceLabel = match ($source) {
                                            'VehicleRepairRealiz' => 'Perbaikan Kendaraan',
                                            'RentCar' => 'Sewa Kendaraan',
                                            default => 'Lainnya',
                                        };
                                    @endphp
                                    <tr>
                                        <td data-label="#">{{ $loop->iteration }}</td>
                                        <td data-label="Sumber" class="text-start">
                                            <strong>{{ $sourceLabel }}</strong><br>
                                            @if ($k->payable)
                                                @if (in_array($source, ['VehicleRepairRealiz', 'RentCar']) && $k->payable->vehicle)
                                                    <small>Kendaraan: {{ $k->payable->vehicle->name ?? '-' }}</small><br>
                                                @endif
                                            @endif
                                        </td>
                                        <td data-label="Tanggal">
                                            @if ($k->payable && method_exists($k->payable, 'getReportDateAttribute'))
                                                {{ \Carbon\Carbon::parse($k->payable->report_date)->format('d-m-Y') }}
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td data-label="Jenis">
                                            <span class="badge {{ $k->type == 1 ? 'bg-success' : 'bg-danger' }}">
                                                {{ $k->type == 1 ? 'Masuk' : 'Keluar' }}
                                            </span>
                                        </td>
                                        <td data-label="Jumlah" class="fw-bold">
                                            Rp{{ number_format($k->amount, 0, ',', '.') }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="table-light">
                                <tr>
                                    <th colspan="4" class="text-end">Total Pemasukan:</th>
                                    <th class="text-end text-success">
                                        Rp{{ number_format($kas->where('type', 1)->sum('amount'), 0, ',', '.') }}
                                    </th>
                                </tr>
                                <tr>
                                    <th colspan="4" class="text-end">Total Pengeluaran:</th>
                                    <th class="text-end text-danger">
                                        Rp{{ number_format($kas->where('type', 2)->sum('amount'), 0, ',', '.') }}
                                    </th>
                                </tr>
                                <tr>
                                    <th colspan="4" class="text-end">Total Keseluruhan:</th>
                                    <th class="text-end fw-bold">
                                        Rp{{ number_format($kas->sum('amount'), 0, ',', '.') }}
                                    </th>
                                </tr>
                            </tfoot>

                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('js')
    <script>
        new DataTable('#dataTableKas');
    </script>
@endpush