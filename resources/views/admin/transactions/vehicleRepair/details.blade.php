@extends('admin.template.template')
@section('title', 'Detail Nota Perbaikan Kendaraan')

@section('content')
@push('css')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css">
    <style>
        .media-box {
            border: 1px solid #dee2e6;
            border-radius: 8px;
            overflow: hidden;
            height: 100%;
            background-color: #f9f9f9;
        }

        .media-box img,
        .media-box video {
            width: 100%;
            height: 200px;
            object-fit: cover;
            cursor: pointer;
        }

        .media-box-body {
            padding: 12px;
        }

        .media-box-title {
            font-weight: bold;
            font-size: 16px;
        }

        .media-box-text {
            font-size: 14px;
            margin-bottom: 4px;
        }
    </style>
@endpush

<div class="app-content-header py-3">
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center flex-wrap">
            <h3 class="mb-2">Detail Nota Perbaikan Kendaraan</h3>
            <div class="d-flex gap-2 mt-2 mt-md-0">
                <a href="{{ url('transactions/vehicleRepairReal/pdf/' . $vehicleRepairReal->vehcileRepairRealId) }}" class="btn btn-outline-secondary" target="_blank">
                    Cetak PDF
                </a>
                <a href="{{ url('transactions/vehicleRepairReal') }}" class="btn btn-outline-primary">
                    ← Kembali
                </a>
            </div>
        </div>
    </div>
</div>

<div class="app-content">
    
    <div class="container-fluid">
        <div class="card shadow-sm">
            <div class="card-header bg-light d-flex justify-content-between flex-wrap">
                <div class="text-end mt-2 mt-md-0">
                    <small class="text-muted">Tanggal: {{ \Carbon\Carbon::parse($vehicleRepairReal->submission_date)->format('d M Y') }}</small>
                </div>
            </div>

            <div class="card-body">
                <h6 class="text-uppercase text-primary mb-3">Informasi Laporan Pengajuan</h6>
                <table class="table table-bordered">
                    <tr>
                        <th style="width: 30%">Tanggal Laporan Pengajuan Perbaikan</th>
                        <td>{{ $vehicleRepairReal->vehicleRepair->submission_date }}</td>
                    </tr>
                    <tr>
                        <th>Kendaraan Yang Diajukan</th>
                        <td>{{ $vehicleRepairReal->vehicleRepair->vehicle->name }} - {{ $vehicleRepairReal->vehicleRepair->vehicle->plate_number }}</td>
                    </tr>
                    <tr>
                        <th>Diajukan Oleh</th>
                        <td>
                            {{ $vehicleRepairReal->vehicleRepair->user?->admin->name
                                ?? $vehicleRepairReal->vehicleRepair->user?->supervisor?->name
                                ?? $vehicleRepairReal->vehicleRepair->user?->employee?->name
                                ?? 'No PIC' }}
                        </td>
                    </tr>
                </table>

                <h6 class="text-uppercase text-primary mb-3 mt-4">Detail Nota Perbaikan Kendaraan</h6>
                <table class="table table-bordered">
                    <tr>
                        <th style="width: 30%">Tanggal Selesai Perbaikan</th>
                        <td>{{ $vehicleRepairReal->completeDate ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Catatan Nota Perbaikan</th>
                        <td>{{ $vehicleRepairReal->notes }}</td>
                    </tr>
                    <tr>
                        <th>Nominal Perbaikan</th>
                        <td><strong>Rp {{ number_format($vehicleRepairReal->paymentAmount->first()->amount ?? 0, 0, ',', '.') }}</strong></td>
                    </tr>
                </table>

                @if($vehicleRepairReal->photo->count())
                    <h6 class="text-uppercase text-primary mb-3 mt-4">Foto Bukti Pembayaran</h6>
                    <div class="row g-3">
                        @if($vehicleRepairReal->photo )
                            <div class="col-md-3 col-sm-4 col-6">
                                <div class="border rounded shadow-sm overflow-hidden media-box">
                                    <img 
                                        src="{{ asset('storage/'.$vehicleRepairReal->photo->first()->path) }}"
                                        style="max-height: 120px; cursor: pointer;"
                                        class="img-thumbnail"
                                        data-bs-toggle="modal"
                                        data-bs-target="#imageModal"
                                        data-bs-image="{{ asset('storage/'.$vehicleRepairReal->photo->first()->path) }}"
                                        alt="Foto Pembayaran"
                                    >
                                </div>
                            </div>
                        @else
                            <em>Belum ada foto</em>
                        @endif
                    </div>
                @endif
            </div>

        </div>
    </div>
</div>


<div class="modal fade" id="imageModal" tabindex="-1" aria-labelledby="imageModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
      <div class="modal-body p-0">
        <img src="" id="imageModalSrc" class="img-fluid w-100" alt="Preview Besar">
      </div>
    </div>
  </div>
</div>

@push('js')
    <script>
        const imageModal = document.getElementById('imageModal');
        imageModal.addEventListener('show.bs.modal', event => {
            const button = event.relatedTarget;
            const imageSrc = button.getAttribute('data-bs-image');
            const modalImage = document.getElementById('imageModalSrc');
            modalImage.src = imageSrc;
        });
    </script>
@endpush
@endsection
