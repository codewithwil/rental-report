@extends('admin.template.template')
@section('title', 'Detail Sewa Kendaraan Rental')

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

    .media-box img {
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
            <h3 class="mb-2">Detail Sewa Kendaraan Rental</h3>
            <div class="d-flex gap-2 mt-2 mt-md-0">
                {{-- <a href="{{ url('transactions/rentCar/pdf/' . $rentCar->rentCarId) }}" class="btn btn-outline-secondary" target="_blank">
                    Cetak PDF
                </a> --}}
                <a href="{{ url('transactions/rentCar') }}" class="btn btn-outline-primary">
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
                    <small class="text-muted">Tanggal Mulai Sewa: {{ \Carbon\Carbon::parse($rentCar->startDate)->format('d M Y') }}</small>
                </div>
            </div>

            <div class="card-body">
                <h6 class="text-uppercase text-primary mb-3">Informasi Penyewaan</h6>
                <table class="table table-bordered">
                    <tr>
                        <th style="width: 30%">Nama Penyewa</th>
                        <td>{{ $rentCar->renter_name }}</td>
                    </tr>
                    <tr>
                        <th>Alamat Penyewa</th>
                        <td>{{ $rentCar->renter_address }}</td>
                    </tr>
                    <tr>
                        <th>No. Telepon Penyewa</th>
                        <td>{{ $rentCar->renter_phone }}</td>
                    </tr>
                    <tr>
                        <th>Kendaraan Disewa</th>
                        <td>{{ $rentCar->vehicle->name ?? '-' }} - {{ $rentCar->vehicle->plate_number ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Tanggal Sewa</th>
                        <td>{{ \Carbon\Carbon::parse($rentCar->startDate)->format('d M Y') }} s/d {{ \Carbon\Carbon::parse($rentCar->endDate)->format('d M Y') }}</td>
                    </tr>
                    <tr>
                        <th>Harga per Hari</th>
                        <td>Rp {{ number_format($rentCar->pricePerDay, 0, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <th>Total Estimasi Biaya</th>
                        <td>
                            @php
                                $days = \Carbon\Carbon::parse($rentCar->startDate)->diffInDays(\Carbon\Carbon::parse($rentCar->endDate)) + 1;
                                $total = $days * $rentCar->pricePerDay;
                            @endphp
                            <strong>Rp {{ number_format($total, 0, ',', '.') }}</strong>
                            <br>
                            <small class="text-muted">({{ $days }} hari × Rp {{ number_format($rentCar->pricePerDay, 0, ',', '.') }})</small>
                        </td>
                    </tr>
                    <tr>
                        <th>Catatan</th>
                        <td>{{ $rentCar->notes ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Status</th>
                        <td>
                            @php
                                $statuses = ['Pending', 'Sedang Disewa', 'Selesai', 'Dibatalkan'];
                            @endphp
                            <span class="badge bg-info">{{ $statuses[$rentCar->status] ?? 'Unknown' }}</span>
                        </td>
                    </tr>
                    <tr>
                        <th>Jumlah Pembayaran</th>
                        <td>
                            <strong>Rp {{ number_format($rentCar->paymentAmount->first()->amount ?? 0, 0, ',', '.') }}</strong>
                        </td>
                    </tr>
                </table>

                @if($rentCar->photo->count())
                    <h6 class="text-uppercase text-primary mb-3 mt-4">Bukti Pembayaran</h6>
                    <div class="row g-3">
                        @foreach($rentCar->photo as $photo)
                            <div class="col-md-3 col-sm-4 col-6">
                                <div class="border rounded shadow-sm overflow-hidden media-box">
                                    <img 
                                        src="{{ asset('storage/'.$photo->path) }}"
                                        class="img-thumbnail"
                                        data-bs-toggle="modal"
                                        data-bs-target="#imageModal"
                                        data-bs-image="{{ asset('storage/'.$photo->path) }}"
                                        alt="Bukti Pembayaran"
                                    >
                                </div>
                            </div>
                        @endforeach
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
        <img src="" id="imageModalSrc" class="img-fluid w-100" alt="Preview Gambar">
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
        document.getElementById('imageModalSrc').src = imageSrc;
    });
</script>
@endpush
@endsection
