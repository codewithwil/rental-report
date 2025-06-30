@extends('admin.template.template')
@section('title', 'Tambah Data Pengajuan Perbaikan Kendaraan')
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
                <h3 class="mb-0">Tambah Data Nota Perbaikan Kendaraan</h3>
            </div>
            <div class="col-md-6">
                <ol class="breadcrumb float-md-end mb-0">
                    <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Nota Perbaikan Kendaraan</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="app-content">
    <div class="container-fluid">
        <form id="repairForm" action="{{ url('/transactions/vehicleRepairReal/store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Form Nota</h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="completeDate" class="form-label">Tanggal Selesai Perbaikan</label>
                            <input type="date" id="completeDate" name="completeDate" class="form-control" value="{{ date('Y-m-d') }}" required>
                        </div>
                        <div class="col-md-6">
                            <label for="vehicleRep_id" class="form-label">Tanggal Pengajuan Perbaikan</label>
                            <select id="VehicleRepSelect" name="vehicleRep_id" class="form-control" required>
                                <option value="">-- Pilih Tanggal Pengajuan --</option>
                                @foreach($vehicle as $item)
                                    <option value="{{ $item->vehicleRepId }}">{{ $item->submission_date }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="amount" class="form-label">Nominal</label>
                            <input type="number" min="0" id="amount" name="amount" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label for="photos" class="form-label">Foto Bukti Transfer</label>
                            <input type="file" class="filepond" name="photos" />
                        </div>
                        <div class="col-12">
                            <label for="notes" class="form-label">Catatan</label>
                            <textarea name="notes" class="form-control" rows="5" placeholder="Jelaskan kerusakan kendaraan">{{ old('notes') }}</textarea>
                        </div>
                    </div>
                </div>
            <div class="card-footer d-flex justify-content-start">
                <button type="submit" class="btn btn-success">Simpan</button>
                <a href="{{ url('/report/vehicleRepair') }}" class="btn btn-secondary ms-2">Kembali</a>
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

    new TomSelect("#VehicleRepSelect", {
        placeholder: "Pilih Laporan Pengajuan Perbaikan",
        maxItems: 1,
        plugins: ['remove_button'],
        closeAfterSelect: true,
    });
</script>
@endpush