@extends('admin.template.template')
@section('title', 'Tambah Data Pengembalian Kendaraan Rental')
@section('content')

<div class="app-content-header mb-3">
    <div class="container-fluid">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h3 class="mb-0">Tambah Data Pengembalian Kendaraan Rental</h3>
            </div>
            <div class="col-md-6">
                <ol class="breadcrumb float-md-end mb-0">
                    <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Pengembalian Kendaraan Rental</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="app-content">
    <div class="container-fluid">
        <form id="rentCarForm" action="{{ url('/transactions/returnRenCar/store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Form Pengembalian Kendaraan Rental</h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label for="vehicle_id" class="form-label">Sewa Aktif</label>
                            <select id="rentCar_id" name="rentCar_id" class="form-control" required>
                                <option value="">-- Pilih Data Sewa Aktif --</option>
                                @foreach($rentCar as $item)
                                    <option 
                                        value="{{ $item->rentCarId }}"
                                        data-name="{{ $item->renter_name }}"
                                        data-phone="{{ $item->renter_phone }}"
                                        data-address="{{ $item->renter_address }}"
                                    >
                                        {{ $item->renter_name }} - {{ $item->vehicle->name ?? '-' }} ({{ $item->startDate }} s/d {{ $item->endDate }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="renter_name" class="form-label">Nama Penyewa</label>
                            <input type="text" name="return_name" id="renter_name" class="form-control">
                        </div>
                        <div class="col-md-4">
                            <label for="renter_phone" class="form-label">No Telepon Penyewa</label>
                            <input type="text" id="renter_phone" name="return_phone" class="form-control" required>
                        </div>

                        <div class="col-md-6">
                            <label for="renter_address" class="form-label">Alamat Penyewa</label>
                            <textarea name="return_address" id="renter_address" class="form-control" rows="3" required></textarea>
                        </div>
                        <div class="col-md-6">
                            <label for="end_date" class="form-label">Tanggal Selesai Sewa</label>
                            <input type="date" name="return_date" id="end_date" class="form-control" value="{{ date('Y-m-d') }}" required>
                        </div>

                        <div class="col-md-12">
                            <label for="notes" class="form-label">Catatan</label>
                            <textarea name="notes" class="form-control" rows="3"></textarea>
                        </div>
                    </div>
                </div>
                <div class="card-footer d-flex justify-content-start">
                    <button type="submit" class="btn btn-success">Simpan</button>
                    <a href="{{ url('/transactions/returnRentCar') }}" class="btn btn-secondary ms-2">Kembali</a>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@push('js')
<script>
document.getElementById('rentCar_id').addEventListener('change', function () {
    const selected = this.options[this.selectedIndex];
    document.getElementById('renter_name').value = selected.getAttribute('data-name') || '';
    document.getElementById('renter_phone').value = selected.getAttribute('data-phone') || '';
    document.getElementById('renter_address').value = selected.getAttribute('data-address') || '';
});
</script>
@endpush
