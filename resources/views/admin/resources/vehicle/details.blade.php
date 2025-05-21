@extends('admin.template.template')
@section('title', 'Detail Data Kendaraan')

@section('content')
@push('css')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css">
@endpush

<div class="app-content-header">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6"><h3 class="mb-0">Detail Kendaraan: {{ $vehicle->name }}</h3></div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                    <li class="breadcrumb-item"><a href="/dashboard">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ url('transactions/vehicle') }}">Data Kendaraan</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ $vehicle->name }}</li>
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
                    <div class="card-header"><h3 class="card-title">Informasi Kendaraan</h3></div>
                    <div class="card-body">
                        <table class="table table-bordered">
                            <tr>
                                <th>Foto Kendaraan</th>
                                <td>
                                    @if($vehicle->photo)
                                        <img src="{{ asset('storage/'.$vehicle->photo) }}" alt="Foto Kendaraan" style="max-height: 120px;" class="img-thumbnail">
                                    @else
                                        <em>Belum ada foto</em>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th>Nama Kendaraan</th>
                                <td>{{ $vehicle->name ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th>Plat Nomor</th>
                                <td>{{ $vehicle->plate_number ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th>Warna</th>
                                <td>{{ $vehicle->color ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th>Tahun</th>
                                <td>{{ $vehicle->year ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th>Cabang</th>
                                <td>{{ $vehicle->branch->address ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th>Kategori</th>
                                <td>{{ $vehicle->category->name ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th>Merk</th>
                                <td>{{ $vehicle->brand->name ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th>Tanggal Uji Terakhir</th>
                                <td>{{ $vehicle->last_inspection_date ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th>Masa Berlaku KIR</th>
                                <td>{{ $vehicle->kir_expiry_date ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th>Tanggal Pajak</th>
                                <td>{{ $vehicle->tax_date ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th>Catatan</th>
                                <td>{{ $vehicle->note ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th>Status</th>
                                <td>
                                    {{ $vehicle->status_label ?? 'Tidak diketahui' }}
                                </td>
                            </tr>
                            <tr>
                                <th>Dibuat Pada</th>
                                <td>{{ \Carbon\Carbon::parse($vehicle->created_at)->format('d/m/Y') }}</td>
                            </tr>
                            <tr>
                                <th>Diperbarui Pada</th>
                                <td>{{ \Carbon\Carbon::parse($vehicle->updated_at)->format('d/m/Y') }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
                <a href="{{ url('/setting/vehicle') }}" class="btn btn-primary">Kembali</a>
            </div>
        </div>
    </div>
</div>

@push('js')
<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
@endpush
@endsection
