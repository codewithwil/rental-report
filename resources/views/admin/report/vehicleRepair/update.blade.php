@extends('admin.template.template')
@section('title', 'Edit Data Pengajuan Perbaikan Kendaraan')
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
            <div class="col-sm-6"><h3 class="mb-0">Edit Data Pengajuan Perbaikan Kendaraan</h3></div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                    <li class="breadcrumb-item"><a href="{{url('/dashboard')}}">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Pengajuan Perbaikan Kendaraan</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="app-content">
    <div class="container-fluid">
        <form id="repairForm" action="{{ url('report/vehicleRepair/update/'.$vehicleRepair->vehicleRepId) }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="card mb-4" style="border-left: 5px solid #007bff;">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Form Pengajuan</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="mb-3 col-md-6">
                            <label for="submission_date" class="form-label">Tanggal Pengajuan</label>
                            <input type="date" id="submission_date" name="submission_date" class="form-control" value="{{ $vehicleRepair->submission_date }}" required>
                        </div>

                        <div class="mb-3 col-md-6">
                            <label for="vehicle_id" class="form-label">Kendaraan</label>
                            <select id="VehicleSelect" name="vehicle_id" class="form-control" required>
                                <option value="">-- Pilih Kendaraan --</option>
                                @foreach($vehicle as $item)
                                    <option value="{{ $item->vehicleId }}" {{ $vehicleRepair->vehicle_id == $item->vehicleId ? 'selected' : '' }}>
                                        {{ $item->name }} - {{ $item->plate_number }}, {{ \Illuminate\Support\Str::replace('@branch.com', '', $item->branch->email) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="description" class="form-label">Deskripsi Kerusakan</label>
                            <textarea name="description" class="form-control" rows="3" placeholder="Jelaskan kerusakan kendaraan">{{ $vehicleRepair->description }}</textarea>
                        </div>

                        <div class="mb-3">
                            <label for="estimated_cost" class="form-label">Estimasi Biaya Perbaikan</label>
                            <input type="number" name="estimated_cost" class="form-control" placeholder="Masukkan estimasi biaya" min="0" value="{{ $vehicleRepair->estimated_cost }}" required>
                        </div>

                        <div class="mb-3">
                            <label for="photos" class="form-label">Foto Kerusakan</label>
                            <input type="file" class="filepond" name="photos[]" multiple />
                        </div>

                        @if ($vehicleRepair->photo && $vehicleRepair->photo->count())
                            <div class="mb-3">
                                <label class="form-label">Foto Sebelumnya</label>
                                <div class="row" id="existing-photos-wrapper">
                                    @foreach ($vehicleRepair->photo as $img)
                                        <div class="col-md-3 mb-3 existing-photo" data-photo-id="{{ $img->filesId }}">
                                            <div class="position-relative border p-2 rounded">
                                                <button type="button" class="btn-close position-absolute top-0 end-0 m-1 btn-sm btn-remove-photo" aria-label="Close" title="Hapus Foto"></button>
                                                <img src="{{ asset('storage/'.$img->path) }}" class="img-fluid rounded mb-2" alt="Foto Sebelumnya">
                                                <input type="hidden" name="keep_photos[]" value="{{ $img->filesId }}" class="keep-photo-input">
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>
                    <button type="submit" class="btn btn-success">Simpan</button>
                    <a href="{{ url('/report/vehicleRepair') }}" class="btn btn-secondary">Kembali</a>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@push('css')
    <link href="https://unpkg.com/filepond@^4/dist/filepond.css" rel="stylesheet" />
@endpush

@push('js')
<script src="https://unpkg.com/filepond@^4/dist/filepond.js"></script>
<script src="https://unpkg.com/filepond-plugin-file-validate-size/dist/filepond-plugin-file-validate-size.min.js"></script>
<script src="https://unpkg.com/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.min.js"></script>
<link href="https://unpkg.com/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.min.css" rel="stylesheet" />
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
        imageResizeTargetWidth: 300, 
        imageResizeTargetHeight: 300, 
        imageResizeMode: 'contain',
        allowImageTransform: true,
        allowMultiple: true,
        maxFiles: 5,
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
                input.name = `photos[${index}][${key}]`;
                input.classList.add('filepond-hidden-input');
                input.value = key === 'base64' ? file : (key === 'name' ? name : (key === 'type' ? type : size));
                e.target.appendChild(input);
            });
        });

        this.submit();
    });

    document.querySelectorAll('.btn-remove-photo').forEach(button => {
        button.addEventListener('click', function () {
            const photoCard = this.closest('.existing-photo');
            if (photoCard) {
                const hiddenInput = photoCard.querySelector('input[name="keep_photos[]"]');
                if (hiddenInput) {
                    hiddenInput.remove();
                }
                photoCard.remove();
            }
        });
    });

    new TomSelect("#VehicleSelect", {
        placeholder: "Pilih Kendaraan",
        maxItems: 1,
        plugins: ['remove_button'],
        closeAfterSelect: true,
    });
</script>
@endpush
