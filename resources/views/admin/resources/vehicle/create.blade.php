@extends('admin.template.template')
@section('title', 'Tambah Data Kendaraan')
@section('content')
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
                            @foreach($branch as $item)
                                <option value="{{ $item->branchId }}">{{ $item->email }}</option>
                            @endforeach
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
                    <div class="col-md-6">
                        <label class="form-label">Nama Kendaraan</label>
                        <input type="text" name="name" class="form-control" maxlength="50" required>
                    </div>
                    <div class="col-md-6">
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
                            @for ($year = 1900; $year <= $currentYear; $year++)
                                <option value="{{ $year }}">{{ $year }}</option>
                            @endfor
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Tanggal Pemeriksaan Terakhir</label>
                        <input type="date" name="last_inspection_date" class="form-control">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Tanggal Expired KIR</label>
                        <input type="date" name="kir_expiry_date" class="form-control">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Tanggal Pajak</label>
                        <input type="date" name="tax_date" class="form-control">
                    </div>
                    <div class="col-md-12">
                        <label class="form-label">Catatan</label>
                        <textarea name="note" class="form-control" rows="3" required></textarea>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Foto Kendaraan</label>
                        <input type="file" name="photo" class="form-control" accept="image/*" required>
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

            new TomSelect("#yearSelect", {
                placeholder: "Pilih Tahun",
                allowEmptyOption: true
            });
        });
    </script>
@endpush
@endsection

