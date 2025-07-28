@extends('admin.template.template')
@section('title', 'Tambah Data Sewa Kendaraan Rental')
@section('content')

@push('css')
<link href="https://unpkg.com/filepond/dist/filepond.min.css" rel="stylesheet" />
<link href="https://unpkg.com/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.min.css" rel="stylesheet" />
<link href="https://unpkg.com/filepond-plugin-pdf-preview/dist/filepond-plugin-pdf-preview.min.css" rel="stylesheet" />
<link href="https://unpkg.com/filepond@^4/dist/filepond.css" rel="stylesheet" />
<style>
    .filepond--root {
        height: 300px;
        font-size: 1rem;
    }
    .filepond--drop-label {
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 1rem;
    }
    .filepond--panel-root {
        border: 2px dashed #007bff;
        border-radius: 8px;
        background-color: #f8f9fa;
    }
</style>
@endpush

<div class="app-content-header mb-3">
    <div class="container-fluid">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h3 class="mb-0">Tambah Data Sewa Kendaraan Rental</h3>
            </div>
            <div class="col-md-6">
                <ol class="breadcrumb float-md-end mb-0">
                    <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Sewa Kendaraan Rental</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="app-content">
    <div class="container-fluid">
        <form id="rentCarForm" action="{{ url('/transactions/rentCar/store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Form Sewa Kendaraan</h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="vehicle_id" class="form-label">Kendaraan</label>
                            <select id="vehicle_id" name="vehicle_id" class="form-control" required>
                                <option value="">-- Pilih Kendaraan --</option>
                                @foreach($vehicle as $item)
                                    <option value="{{ $item->vehicleId }}">{{ $item->name }} - {{ $item->plate_number }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="price_per_day" class="form-label">Harga per Hari</label>
                            <input type="number" min="0" name="pricePerDay" id="price_per_day" class="form-control" required>
                        </div>
                        <div class="col-md-3">
                            <label for="total_bayar" class="form-label">Total Bayar (otomatis)</label>
                            <input type="text" class="form-control" name="amount" id="total_bayar" readonly>
                        </div>

                        <div class="col-md-6">
                            <label for="renter_name" class="form-label">Nama Penyewa</label>
                            <input type="text" name="renter_name" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label for="renter_phone" class="form-label">No Telepon Penyewa</label>
                            <input type="text" name="renter_phone" class="form-control" required>
                        </div>
                        <div class="col-md-12">
                            <label for="renter_address" class="form-label">Alamat Penyewa</label>
                            <textarea name="renter_address" class="form-control" rows="2" required></textarea>
                        </div>

                        <div class="col-md-6">
                            <label for="start_date" class="form-label">Tanggal Mulai Sewa</label>
                            <input type="date" name="startDate" id="start_date" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label for="end_date" class="form-label">Tanggal Selesai Sewa</label>
                            <input type="date" name="endDate" id="end_date" class="form-control" required>
                        </div>

                        <div class="col-md-6">
                            <label for="photos" class="form-label">Foto Bukti Transfer</label>
                            <input type="file" class="filepond" name="photos" />
                        </div>
                        <div class="col-md-6">
                            <label for="notes" class="form-label">Catatan</label>
                            <textarea name="notes" class="form-control" rows="3"></textarea>
                        </div>
                        <div class="col-md-6">
                            <label for="status" class="form-label">Status Pembayaran</label>
                            <select id="status" name="status" class="form-control">
                                <option value="">-- Pilih Status Pembayaran --</option>
                                <option value="0">Pending</option>
                                <option value="1">Lunas</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="card-footer d-flex justify-content-start">
                    <button type="submit" class="btn btn-success">Simpan</button>
                    <a href="{{ url('/transactions/rentCar') }}" class="btn btn-secondary ms-2">Kembali</a>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@push('js')
<script src="https://unpkg.com/filepond@^4/dist/filepond.js"></script>
<script src="https://unpkg.com/filepond-plugin-file-validate-size/dist/filepond-plugin-file-validate-size.min.js"></script>
<script src="https://unpkg.com/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.min.js"></script>
<script src="https://unpkg.com/filepond-plugin-image-resize/dist/filepond-plugin-image-resize.min.js"></script>
<script src="https://unpkg.com/filepond-plugin-image-transform/dist/filepond-plugin-image-transform.min.js"></script>
<script src="https://unpkg.com/filepond-plugin-file-encode/dist/filepond-plugin-file-encode.min.js"></script>
<script>
    FilePond.registerPlugin(
        FilePondPluginFileValidateSize,
        FilePondPluginImagePreview,
        FilePondPluginImageResize,
        FilePondPluginImageTransform,
        FilePondPluginFileEncode
    );

    const pond = FilePond.create(document.querySelector('.filepond'), {
        imageResizeTargetWidth: 800,
        imageResizeTargetHeight: 800,
        imageResizeMode: 'contain',
        allowImageTransform: true,
        allowMultiple: false,
        maxFiles: 1,
        instantUpload: false,
        server: false,
        allowFileEncode: true,
    });

    document.getElementById('rentCarForm').addEventListener('submit', function(e) {
        e.preventDefault();
        document.querySelectorAll('.filepond-hidden-input').forEach(el => el.remove());

        const fileItem = pond.getFiles()[0];
        if (fileItem) {
            const file = fileItem.getFileEncodeBase64String();
            const name = fileItem.filename;
            const type = fileItem.fileType;
            const size = fileItem.fileSize;

            ['name', 'type', 'size', 'base64'].forEach(key => {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = `photo[${key}]`;
                input.classList.add('filepond-hidden-input');
                input.value = key === 'base64' ? file : (key === 'name' ? name : (key === 'type' ? type : size));
                e.target.appendChild(input);
            });
        }

        this.submit();
    });
</script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const startDateInput = document.getElementById('start_date');
    const endDateInput = document.getElementById('end_date');
    const pricePerDayInput = document.getElementById('price_per_day');
    const totalBayarDisplay = document.getElementById('total_bayar');

    function calculateTotalBayar() {
        const startDate = new Date(startDateInput.value);
        const endDate = new Date(endDateInput.value);
        const pricePerDay = parseFloat(pricePerDayInput.value);

        if (!isNaN(startDate.getTime()) && !isNaN(endDate.getTime()) && !isNaN(pricePerDay)) {
            const timeDiff = endDate - startDate;
            const dayDiff = Math.floor(timeDiff / (1000 * 60 * 60 * 24)) + 1;

            if (dayDiff > 0) {
                const total = dayDiff * pricePerDay;
                totalBayarDisplay.value = total.toFixed(2);
            } else {
                totalBayarDisplay.value = '';
            }
        } else {
            totalBayarDisplay.value = '';
        }
    }

    startDateInput.addEventListener('change', calculateTotalBayar);
    endDateInput.addEventListener('change', calculateTotalBayar);
    pricePerDayInput.addEventListener('input', calculateTotalBayar);
});
</script>
@endpush
