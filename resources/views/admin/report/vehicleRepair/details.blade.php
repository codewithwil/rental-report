@extends('admin.template.template')
@section('title', 'Detail Perbaikan Kendaraan')

@section('content')
<div class="app-content-header py-3">
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center flex-wrap">
            <h3 class="mb-2">Detail Perbaikan Kendaraan</h3>
            <a href="{{ url('report/vehicleRepair') }}" class="btn btn-outline-primary">← Kembali</a>
        </div>
    </div>
</div>

<div class="app-content">
    <div class="container-fluid">

        <div class="card shadow-sm">
            <div class="card-header bg-light d-flex justify-content-between flex-wrap">
                <div>
                    <h5 class="mb-0">{{ $company->name }}</h5>
                </div>
                <div class="text-end mt-2 mt-md-0">
                    <small class="text-muted">Tanggal: {{ \Carbon\Carbon::parse($vehicleRepair->submission_date)->format('d M Y') }}</small>
                </div>
            </div>

            <div class="card-body">
                <h6 class="text-uppercase text-primary mb-3">Informasi Kendaraan</h6>
                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <div class="border p-3 rounded bg-light">
                            <strong>Nama:</strong><br>{{ $vehicleRepair->vehicle->name }}
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="border p-3 rounded bg-light">
                            <strong>Plat Nomor:</strong><br>{{ $vehicleRepair->vehicle->plate_number }}
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="border p-3 rounded bg-light">
                            <strong>Merk:</strong><br>{{ $vehicleRepair->vehicle->brand->name ?? '-' }}
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="border p-3 rounded bg-light">
                            <strong>Cabang:</strong><br>{{ $vehicleRepair->vehicle->branch->address ?? '-' }}
                        </div>
                    </div>
                </div>

                <h6 class="text-uppercase text-primary mb-3">Detail Pengajuan</h6>
                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <div class="border p-3 rounded bg-light">
                            <strong>Diajukan Oleh:</strong><br>{{ $vehicleRepair->user->name ?? '-' }}
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="border p-3 rounded bg-light">
                            <strong>Status:</strong><br>
                            <span class="badge bg-info text-dark">{{ $vehicleRepair->status_repair_label ?? 'Belum Ditentukan' }}</span>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="border p-3 rounded bg-light">
                            <strong>Deskripsi Kerusakan:</strong>
                            <p class="mb-0 mt-1">{{ $vehicleRepair->description }}</p>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="border p-4 rounded bg-warning-subtle text-dark">
                            <strong>Estimasi Biaya:</strong>
                            <h4 class="mt-2">Rp {{ number_format($vehicleRepair->estimated_cost, 0, ',', '.') }}</h4>
                        </div>
                    </div>
                </div>

                @if($vehicleRepair->photo->count())
                    <h6 class="text-uppercase text-primary mb-3">Foto Kerusakan</h6>
                    <div class="row g-3">
                        @foreach ($vehicleRepair->photo as $photo)
                            <div class="col-md-3 col-sm-4 col-6">
                                <div class="border rounded shadow-sm overflow-hidden">
                                    <img src="{{ asset('storage/' . $photo->path) }}" class="img-fluid" style="object-fit: cover; height: 160px; width: 100%;" alt="Foto Kerusakan">
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif

            </div>
        </div>

       <div class="mt-4 d-flex gap-2">
            @if ($vehicleRepair->statusRepair == \App\Models\Report\VehicleRepair\VehicleRepair::STATUSREP_PENDING)
                <form id="approve-form" action="{{ url('report/vehicleRepair/approve', $vehicleRepair->vehicleRepId) }}" method="POST" style="display: none;">
                    @csrf
                    @method('POST')
                </form>
                <button type="button" class="btn btn-success" id="btn-approve">Setujui</button>

                <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#rejectModal">Tolak</button>
            @endif
        </div>
    </div>
</div>

{{-- modal  --}}
<div class="modal fade" id="rejectModal" tabindex="-1" aria-labelledby="rejectModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form action="{{ url('report/vehicleRepair/reject/' . $vehicleRepair->vehicleRepId) }}" method="POST">
        @csrf
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tolak Pengajuan Perbaikan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <div class="modal-body">
                <div class="form-group mb-2">
                    <label for="note">Alasan Penolakan</label>
                    <textarea name="note" id="note" class="form-control" required></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-danger">Kirim Penolakan</button>
            </div>
        </div>
    </form>
  </div>
</div>

@push('js')
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        const btnApprove = document.getElementById('btn-approve');
        const btnReject = document.getElementById('btn-reject');

        btnApprove?.addEventListener('click', function () {
            Swal.fire({
                title: 'Setujui Pengajuan?',
                text: "Apakah Anda yakin ingin menyetujui pengajuan ini?",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#28a745',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, Setujui!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('approve-form').submit();
                }
            });
        });
    });
</script>
@endpush
@endsection
