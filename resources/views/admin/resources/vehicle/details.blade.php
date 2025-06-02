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
                                        <img src="{{ asset('storage/'.$vehicle->photo) }}" alt="Foto Kendaraan" style="max-height: 120px; cursor: pointer;" class="img-thumbnail" data-bs-toggle="modal" data-bs-target="#photoModal">
                                    @else
                                        <em>Belum ada foto</em>
                                    @endif
                                </td>
                            </tr>
                           <tr>
                                <th>Dokumen KIR</th>
                                <td>
                                    @if($vehicle->vehicleDocument?->kir_document)
                                        <button class="btn btn-link p-0" data-bs-toggle="modal" data-bs-target="#kirModal">
                                            Lihat Dokumen KIR
                                        </button>
                                    @else
                                        <em>Belum ada dokumen</em>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th>Dokumen BPKB</th>
                                <td>
                                    @if($vehicle->vehicleDocument?->bpkb_document)
                                        <button class="btn btn-link p-0" data-bs-toggle="modal" data-bs-target="#bpkbModal">
                                            Lihat Dokumen BPKB
                                        </button>
                                    @else
                                        <em>Belum ada dokumen</em>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th>Dokumen STNK</th>
                                <td>
                                    @if($vehicle->vehicleDocument?->stnk_document)
                                        <button class="btn btn-link p-0" data-bs-toggle="modal" data-bs-target="#stnkModal">
                                            Lihat Dokumen STNK
                                        </button>
                                    @else
                                        <em>Belum ada dokumen</em>
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
                                <th>Masa Berlaku KIR</th>
                                <td>{{ $vehicle->vehicleDocument->kir_expiry_date ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th>Tanggal Expired STNK</th>
                                <td>{{ $vehicle->vehicleDocument->stnk_date ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th>Tanggal Expired BPKB</th>
                                <td>{{ $vehicle->vehicleDocument->bpkb_date ?? '-' }}</td>
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

<div class="modal fade" id="photoModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <img src="{{ asset('storage/'.$vehicle->photo) }}" class="w-100">
        </div>
    </div>
</div>
<div class="modal fade" id="kirModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Preview Dokumen KIR</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" style="height: 80vh;">
                <iframe src="{{ asset('storage/'.$vehicle->vehicleDocument?->kir_document) }}" style="width:100%; height:100%;" frameborder="0"></iframe>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="bpkbModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Preview Dokumen BPKB</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" style="height: 80vh;">
                <iframe src="{{ asset('storage/'.$vehicle->vehicleDocument?->bpkb_document) }}" style="width:100%; height:100%;" frameborder="0"></iframe>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="stnkModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Preview Dokumen STNK</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" style="height: 80vh;">
                <iframe src="{{ asset('storage/'.$vehicle->vehicleDocument?->stnk_document) }}" style="width:100%; height:100%;" frameborder="0"></iframe>
            </div>
        </div>
    </div>
</div>

</div>

@push('js')
<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
@endpush
@endsection
