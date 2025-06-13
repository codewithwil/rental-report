@extends('admin.template.template')
@section('title', 'Edit Data Kendaraan')
@section('content')
@push('css')
<link href="https://unpkg.com/filepond/dist/filepond.min.css" rel="stylesheet" />
<link href="https://unpkg.com/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.min.css" rel="stylesheet" />
<link href="https://unpkg.com/filepond-plugin-pdf-preview/dist/filepond-plugin-pdf-preview.min.css" rel="stylesheet" />
<style>
    .filepond--root {
        height: 180px;
        min-height: 180px;
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
            <div class="col-sm-6"><h3 class="mb-0">Edit Data Kendaraan</h3></div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                    <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item">Setting</li>
                    <li class="breadcrumb-item active">Kendaraan</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="app-content">
    <div class="container-fluid">
        <form action="{{ url('setting/vehicle/update/' . $vehicle->vehicleId) }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="card mb-4" style="border-left: 5px solid #007bff;">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Form Edit Data Kendaraan</h5>
                </div>
                <div class="card-body row g-3">
                    <div class="col-md-4">
                        <label class="form-label">Cabang</label>
                        <select id="branchSelect" name="branch_id" class="form-control" autocomplete="off">
                            <option value="">-- Pilih Cabang --</option>
                            @foreach($branch as $item)
                                <option value="{{ $item->branchId }}" {{ $vehicle->branch_id == $item->branchId ? 'selected' : '' }}>
                                    {{ $item->email }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Kategori</label>
                        <select id="categorySelect" name="category_id" class="form-control" autocomplete="off">
                            <option value="">-- Pilih Kategori --</option>
                            @foreach($category as $item)
                                <option value="{{ $item->categoryId }}" {{ $vehicle->category_id == $item->categoryId ? 'selected' : '' }}>
                                    {{ $item->name }} - {{ $item->type_label }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Merk</label>
                        <select id="brandSelect" name="brand_id" class="form-control" autocomplete="off">
                            <option value="">-- Pilih Merk --</option>
                            @foreach($brand as $item)
                                <option value="{{ $item->brandId }}" {{ $vehicle->brand_id == $item->brandId ? 'selected' : '' }}>
                                    {{ $item->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                     <div class="col-md-4">
                        <label class="form-label">Penanggung Jawab</label>
                        <select id="picSelect" name="user_id" class="form-control" placeholder="Pilih Penanggung jawab" autocomplete="off">
                            <option value="">-- Pilih Penanggung Jawab --</option>
                            @foreach($users as $s)
                                <option value="{{ $s->id }}" {{ $vehicle->user_id == $s->id ? 'selected' : ''}}>{{ $s->employee?->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Nama Kendaraan</label>
                        <input type="text" name="name" class="form-control" maxlength="50" required value="{{ $vehicle->name }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Plat Nomor</label>
                        <input type="text" name="plate_number" class="form-control" maxlength="20" required value="{{ $vehicle->plate_number }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Warna</label>
                        <input type="text" name="color" class="form-control" maxlength="20" required value="{{ $vehicle->color }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Tahun</label>
                        <select id="yearSelect" name="year" class="form-control" required>
                            <option value="">-- Pilih Tahun --</option>
                            @php $currentYear = date('Y'); @endphp
                            @for ($year = 1900; $year <= $currentYear; $year++)
                                <option value="{{ $year }}" {{ $vehicle->year == $year ? 'selected' : '' }}>{{ $year }}</option>
                            @endfor
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Tanggal Expired KIR</label>
                        <input type="date" name="kir_expiry_date" class="form-control" value="{{ $vehicle->vehicleDocument?->kir_expiry_date }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Tanggal Expired STNK</label>
                        <input type="date" name="stnk_date" class="form-control" value="{{ $vehicle->vehicleDocument?->stnk_date }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Tanggal Expired BPKB</label>
                        <input type="date" name="bpkb_date" class="form-control" value="{{ $vehicle->vehicleDocument?->bpkb_date }}">
                    </div>
                    <div class="col-md-12">
                        <label class="form-label">Catatan</label>
                        <textarea name="note" class="form-control" rows="3" required>{{ $vehicle->note }}</textarea>
                    </div>
                    
                    <div class="col-md-3">
                        <label class="form-label">Foto Kendaraan</label>
                        <input type="file" name="photo" id="photo" class="filepond" accept="image/*">
                    </div>

                   <div class="col-md-3">
                        <label class="form-label">Dokumen KIR</label>
                        <input type="file" name="kir_document" id="kir_document" class="filepond" accept="application/pdf,image/*">
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">Dokumen STNK</label>
                        <input type="file" name="stnk_document" id="stnk_document" class="filepond" accept="application/pdf,image/*">
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">Dokumen BPKB</label>
                        <input type="file" name="bpkb_document" id="bpkb_document" class="filepond" accept="application/pdf,image/*">
                    </div>

                    <div class="col-md-6">
                        <label for="status" class="form-label">Status Kendaraan</label>
                        <select name="status" id="status" class="form-control">
                            <option value="">-- Pilih Tipe Kendaraan --</option>
                            <option value="1" {{ old('status', $vehicle->status ?? '') == '1' ? 'selected' : '' }}>Tidak aktif</option>
                            <option value="2" {{ old('status', $vehicle->status ?? '') == '2' ? 'selected' : '' }}>Aktif</option>
                            <option value="3" {{ old('status', $vehicle->status ?? '') == '3' ? 'selected' : '' }}>Perbaikan</option>
                        </select>  
                    </div>
                    <div class="col-12 mt-3">
                        <button type="submit" class="btn btn-primary">Simpan</button>
                        <button type="reset" class="btn btn-warning">Reset Form</button>
                        <a href="{{ url('setting/vehicle') }}" class="btn btn-secondary">Kembali</a>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

@push('js')
<script src="https://unpkg.com/filepond/dist/filepond.min.js"></script>
<script src="https://unpkg.com/filepond-plugin-file-validate-type/dist/filepond-plugin-file-validate-type.min.js"></script>
<script src="https://unpkg.com/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.min.js"></script>
<script src="https://unpkg.com/filepond-plugin-pdf-preview/dist/filepond-plugin-pdf-preview.min.js"></script>
<script src="https://unpkg.com/filepond-plugin-file-encode/dist/filepond-plugin-file-encode.min.js"></script>
<script>
    FilePond.registerPlugin(
        FilePondPluginFileValidateType, 
        FilePondPluginImagePreview, 
        FilePondPluginPdfPreview, 
        FilePondPluginFileEncode
    );

    FilePond.setOptions({
            server: null,
            allowMultiple: false,
            acceptedFileTypes: ['image/*', 'application/pdf'],
            maxFileSize: '20MB',
            instantUpload: false,
        });
    const photo = FilePond.create(document.querySelector('input[name="photo"]'), {
        allowMultiple: false,
        acceptedFileTypes: ['image/*'],
        files: [
            @if($vehicle->photo)
            {
                source: '{{ asset('storage/' . $vehicle->photo) }}',
            }
            @endif
        ]
    });

    const kirDocument = FilePond.create(document.querySelector('input[name="kir_document"]'), {
        allowMultiple: false,
        acceptedFileTypes: ['application/pdf'],
        files: [
            @if($vehicle->vehicleDocument->kir_document)
            {
                source: '{{ asset('storage/' . $vehicle->vehicleDocument->kir_document) }}',
            }
            @endif
        ]
    });

    const stnkDocument = FilePond.create(document.querySelector('input[name="stnk_document"]'), {
        allowMultiple: false,
        acceptedFileTypes: ['application/pdf'],
        files: [
            @if($vehicle->vehicleDocument->stnk_document)
            {
                source: '{{ asset('storage/' . $vehicle->vehicleDocument->stnk_document) }}',
            }
            @endif
        ]
    });

    const bpkbDocument = FilePond.create(document.querySelector('input[name="bpkb_document"]'), {
        allowMultiple: false,
        acceptedFileTypes: ['application/pdf'],
        files: [
            @if($vehicle->vehicleDocument->bpkb_document)
            {
                source: '{{ asset('storage/' . $vehicle->vehicleDocument->bpkb_document) }}',
            }
            @endif
        ]
    });

    new TomSelect("#branchSelect", { placeholder: "Pilih Cabang", allowEmptyOption: true });
    new TomSelect("#categorySelect", { placeholder: "Pilih Kategori", allowEmptyOption: true });
    new TomSelect("#brandSelect", { placeholder: "Pilih Merk", allowEmptyOption: true });
    new TomSelect("#picSelect", { placeholder: "Pilih Penanggung Jawab", allowEmptyOption: true });
    new TomSelect("#yearSelect", { placeholder: "Pilih Tahun", allowEmptyOption: true });
</script>
@endpush


@endsection
