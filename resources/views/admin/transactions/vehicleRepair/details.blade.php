@extends('admin.template.template')
@section('title', 'Detail Nota Perbaikan Kendaraan')

@section('content')
<div class="app-content-header py-3">
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center flex-wrap">
            <h3 class="mb-2">Detail Nota Perbaikan Kendaraan</h3>
            <a href="{{ url('transactions/vehicleRepairReal') }}" class="btn btn-outline-primary">← Kembali</a>
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
                <div class="row g-3 mb-4">
                    <div class="col-md-4">
                        <div class="border p-3 rounded bg-light">
                            <strong>Tanggal Laporan Pengajuan Perbaikan :</strong><br>{{ $vehicleRepairReal->vehicleRepair->submission_date }}
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="border p-3 rounded bg-light">
                            <strong>Kendaraan Yang Diajukan:</strong><br>{{ $vehicleRepairReal->vehicleRepair->vehicle->name }} - {{ $vehicleRepairReal->vehicleRepair->vehicle->plate_number }}
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="border p-3 rounded bg-light">
                            <strong>Diajukan Oleh:</strong><br>{{ $vehicleRepairReal->vehicleRepair->user?->admin->name ?? $vehicleRepairReal->vehicleRepair->user?->supervisor?->name ?? $vehicleRepairReal->vehicleRepair->user?->employee?->name ?? 'No PIC' }}
                        </div>
                    </div>
                </div>

                <h6 class="text-uppercase text-primary mb-3">Detail Nota Perbaikan Kendaraan</h6>
                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <div class="border p-3 rounded bg-light">
                            <strong>Tanggal Selesai Perbaikan:</strong><br>{{ $vehicleRepairReal->completeDate ?? '-' }}
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="border p-3 rounded bg-light">
                            <strong>Catatan Nota Perbaikan:</strong>
                            <p class="mb-0 mt-1">{{ $vehicleRepairReal->notes }}</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="border p-4 rounded bg-warning-subtle text-dark">
                            <strong>Nominal Perbaikan:</strong>
                            <h4 class="mt-2">Rp {{ number_format($vehicleRepairReal->paymentAmount->first()->amount ?? 0, 0, ',', '.') }}</h4>
                        </div>
                    </div>
                    <div class="col-md-6">
                        @if($vehicleRepairReal->photo->count())
                            <h6 class="text-uppercase text-primary mb-3">Foto Bukti Pembayaran</h6>
                            @foreach ($vehicleRepairReal->photo as $photo)
                                <div class="border rounded shadow-sm overflow-hidden">
                                    <img src="{{ asset('storage/' . $photo->path) }}" class="img-fluid" style="object-fit: cover; height: 160px; width: 100%;" alt="Foto Kerusakan">
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>


            </div>
        </div>

       <div class="mt-4 d-flex gap-2">
            @if ($vehicleRepairReal->statusRepair == \App\Models\Report\VehicleRepair\VehicleRepair::STATUSREP_PENDING)
                <form id="approve-form" action="{{ url('report/vehicleRepairReal/approve', $vehicleRepairReal->vehicleRepId) }}" method="POST" style="display: none;">
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
    <form action="{{ url('report/vehicleRepairReal/reject/' . $vehicleRepairReal->vehicleRepId) }}" method="POST">
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
