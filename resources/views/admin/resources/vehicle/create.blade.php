@extends('admin.template.template')
@section('title', 'Tambah Data Kendaraan')
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
            <div class="col-sm-6"><h3 class="mb-0">Tambah Data Kendaraan</h3></div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                    <li class="breadcrumb-item"><a href="{{url('/dashboard')}}">Dashboard</a></li>
                    <li class="breadcrumb-item">Setting</li>
                    <li class="breadcrumb-item active">Kendaraan</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="app-content">
    <div class="container-fluid">
        <form action="{{ url('setting/vehicle/store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="card mb-4" style="border-left: 5px solid #007bff;">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Form Tambah Data Kendaraan</h5>
                </div>
                <div class="card-body row g-3">
                    <div class="col-md-4">
                        <label class="form-label">Cabang</label>
                        <select id="branchSelect" name="branch_id" class="form-control" placeholder="Pilih Cabang" autocomplete="off">
                            <option value="">-- Pilih Cabang --</option>
                            @if (Auth::user()->branch)
                                    <option value="{{ Auth::user()->branch->branchId }}" selected>
                                        {{ Auth::user()->branch->email }}
                                    </option>
                                @else
                                    @foreach ($branch as $b)
                                        <option value="{{ $b->branchId }}">{{ $b->email }}</option>
                                    @endforeach
                            @endif
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Kategori</label>
                        <select id="categorySelect" name="category_id" class="form-control" placeholder="Pilih Kategori" autocomplete="off">
                            <option value="">-- Pilih Kategori --</option>
                            @foreach($category as $item)
                                <option value="{{ $item->categoryId }}">{{ $item->name }} - {{ $item->type_label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Merk</label>
                        <select id="brandSelect" name="brand_id" class="form-control" placeholder="Pilih Merk" autocomplete="off">
                            <option value="">-- Pilih Merk --</option>
                            @foreach($brand as $item)
                                <option value="{{ $item->brandId }}">{{ $item->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Penanggung Jawab</label>
                        <select id="picSelect" name="user_id" class="form-control" placeholder="Pilih Penanggung jawab" autocomplete="off">
                            <option value="">-- Pilih Penanggung Jawab --</option>
                            @foreach($users as $s)
                                <option value="{{ $s->id }}">{{ $s->employee?->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Nama Kendaraan</label>
                        <input type="text" name="name" class="form-control" maxlength="50" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Plat Nomor</label>
                        <input type="text" name="plate_number" class="form-control" maxlength="20" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Warna</label>
                        <input type="text" name="color" class="form-control" maxlength="20" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Tahun</label>
                        <select id="yearSelect" name="year" class="form-control" required>
                            <option value="">-- Pilih Tahun --</option>
                            @php
                                $currentYear = date('Y');
                            @endphp
                            @for ($year = 1980; $year <= $currentYear; $year++)
                                <option value="{{ $year }}">{{ $year }}</option>
                            @endfor
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Tanggal Expired KIR</label>
                        <input type="date" name="kir_expiry_date" class="form-control">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Tanggal Expired STNK</label>
                        <input type="date" name="stnk_date" class="form-control">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Tanggal Expired BPKB</label>
                        <input type="date" name="bpkb_date" class="form-control">
                    </div>
                    <div class="col-md-12">
                        <label class="form-label">Catatan</label>
                        <textarea name="note" class="form-control" rows="5" required></textarea>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Foto Kendaraan</label>
                        <input type="file" name="photo" class="filepond" accept="image/*" required />
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">Dokumen KIR</label>
                        <input type="file" name="kir_document" class="filepond" accept="application/pdf,image/*">
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">Dokumen STNK</label>
                        <input type="file" name="stnk_document" class="filepond" accept="application/pdf,image/*">
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">Dokumen BPKB</label>
                        <input type="file" name="bpkb_document" class="filepond" accept="application/pdf,image/*">
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
<script src="https://unpkg.com/filepond-plugin-file-validate-size/dist/filepond-plugin-file-validate-size.min.js"></script>
<script src="https://unpkg.com/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.min.js"></script>
<script src="https://unpkg.com/filepond-plugin-pdf-preview/dist/filepond-plugin-pdf-preview.min.js"></script>
<script src="https://unpkg.com/filepond-plugin-file-encode/dist/filepond-plugin-file-encode.min.js"></script>

<script>
    FilePond.registerPlugin(
        FilePondPluginFileValidateType,
        FilePondPluginFileValidateSize,
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

    FilePond.parse(document.body);
</script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        new TomSelect("#branchSelect", {
            placeholder: "Pilih Cabang",
            allowEmptyOption: true
        });

        new TomSelect("#categorySelect", {
            placeholder: "Pilih Kategori",
            allowEmptyOption: true
        });

        new TomSelect("#brandSelect", {
            placeholder: "Pilih Merk",
            allowEmptyOption: true
        });

        new TomSelect("#picSelect", {
            placeholder: "Pilih Penanggung Jawab",
            allowEmptyOption: true
        });


        new TomSelect("#yearSelect", {
            placeholder: "Pilih Tahun",
            allowEmptyOption: true
        });
    });
</script>
@endpush
@endsection

