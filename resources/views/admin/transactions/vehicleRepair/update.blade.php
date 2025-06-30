@extends('admin.template.template')
@section('title', 'Edit Data Nota Perbaikan Kendaraan')
@section('content')

@push('css')
<link href="https://unpkg.com/filepond/dist/filepond.min.css" rel="stylesheet" />
<link href="https://unpkg.com/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.min.css" rel="stylesheet" />
<link href="https://unpkg.com/filepond-plugin-pdf-preview/dist/filepond-plugin-pdf-preview.min.css" rel="stylesheet" />
<style>
    .filepond--root {
        height: 300px;
        min-height: 300px;
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

<div class="app-content-header">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6">
                <h3 class="mb-0">Edit Data Nota Perbaikan Kendaraan</h3>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                    <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Nota Perbaikan Kendaraan</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="app-content">
    <div class="container-fluid">
        <form id="repairForm" action="{{ url('transactions/vehicleRepairReal/update/'.$vehicleRepairReal->vehcileRepairRealId) }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="card mb-4" style="border-left: 5px solid #007bff;">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Form Pengajuan</h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="completeDate" class="form-label">Tanggal Selesai Perbaikan</label>
                            <input type="date" id="completeDate" name="completeDate" class="form-control"
                                value="{{ old('completeDate', $vehicleRepairReal->completeDate) }}" required>
                        </div>

                        <div class="col-md-6">
                            <label for="vehicleRep_id" class="form-label">Tanggal Pengajuan Perbaikan</label>
                            <select id="VehicleRepSelect" name="vehicleRep_id" class="form-control" required>
                                <option value="">-- Pilih Tanggal Pengajuan --</option>
                                @foreach($vehicleRepair as $item)
                                <option value="{{ $item->vehicleRepId }}" {{ $item->vehicleRepId == $vehicleRepairReal->vehicleRep_id ? 'selected' : '' }}>
                                    {{ $item->submission_date }}
                                </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label for="amount" class="form-label">Nominal</label>
                            <input type="number" min="0" id="amount" name="amount" class="form-control"
                                value="{{ old('amount', $vehicleRepairReal->paymentAmount->first()->amount ?? '') }}" required>
                        </div>

                        <div class="col-md-4">
                            <label for="photo" class="form-label">Foto Bukti Pembayaran (Baru)</label>
                            <input type="file" class="filepond" name="photo" id="photo" />
                        </div>

                        @if ($vehicleRepairReal->photo && $vehicleRepairReal->photo->count())
                        <div class="col-md-4">
                            <label class="form-label">Foto Sebelumnya</label>
                            <div class="row g-2" id="existing-photos-wrapper">
                                @foreach ($vehicleRepairReal->photo as $img)
                                <div class="col-md-6 col-sm-6 existing-photo" data-photo-id="{{ $img->filesId }}">
                                    <div class="border rounded p-2 position-relative">
                                        <img src="{{ asset('storage/'.$img->path) }}" 
                                             class="img-fluid rounded w-100" 
                                             style="max-height: 220px; object-fit: cover;" 
                                             alt="Foto Sebelumnya">
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        @endif

                        <div class="col-md-12">
                            <label for="notes" class="form-label">Catatan</label>
                            <textarea name="notes" class="form-control" rows="5" placeholder="Jelaskan kerusakan kendaraan">{{ old('notes', $vehicleRepairReal->notes) }}</textarea>
                        </div>

                        <div class="col-12 d-flex justify-content-start gap-2 mt-3">
                            <button type="submit" class="btn btn-success">Simpan</button>
                            <a href="{{ url('/transactions/vehicleRepairReal') }}" class="btn btn-secondary">Kembali</a>
                        </div>

                    </div>
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

    document.getElementById('repairForm').addEventListener('submit', function(e) {
        e.preventDefault();
        document.querySelectorAll('.filepond-hidden-input').forEach(el => el.remove());

        pond.getFiles().forEach((fileItem, index) => {
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
        });

        this.submit();
    });

    new TomSelect("#VehicleRepSelect", {
        placeholder: "Pilih Kendaraan",
        maxItems: 1,
        plugins: ['remove_button'],
        closeAfterSelect: true,
    });
</script>
@endpush
