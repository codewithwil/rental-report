@extends('admin.template.template')
@section('title', 'Edit Data Kendaraan')
@section('content')

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
                    <div class="col-md-6">
                        <label class="form-label">Nama Kendaraan</label>
                        <input type="text" name="name" class="form-control" maxlength="50" required value="{{ $vehicle->name }}">
                    </div>
                    <div class="col-md-6">
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
                        <label class="form-label">Tanggal Pemeriksaan Terakhir</label>
                        <input type="date" name="last_inspection_date" class="form-control" value="{{ $vehicle->last_inspection_date }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Tanggal Expired KIR</label>
                        <input type="date" name="kir_expiry_date" class="form-control" value="{{ $vehicle->kir_expiry_date }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Tanggal Pajak</label>
                        <input type="date" name="tax_date" class="form-control" value="{{ $vehicle->tax_date }}">
                    </div>
                    <div class="col-md-12">
                        <label class="form-label">Catatan</label>
                        <textarea name="note" class="form-control" rows="3" required>{{ $vehicle->note }}</textarea>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Foto Kendaraan</label>
                        <input type="file" name="photo" class="form-control" accept="image/*">
                        @if($vehicle->photo)
                            <small class="text-muted">Foto saat ini:</small><br>
                            <img src="{{ asset('storage/'.$vehicle->photo) }}" alt="Foto Kendaraan" class="img-thumbnail mt-1" style="max-height: 120px;">
                        @endif
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
    <script>
        new TomSelect("#branchSelect", { placeholder: "Pilih Cabang", allowEmptyOption: true });
        new TomSelect("#categorySelect", { placeholder: "Pilih Kategori", allowEmptyOption: true });
        new TomSelect("#brandSelect", { placeholder: "Pilih Merk", allowEmptyOption: true });
        new TomSelect("#yearSelect", { placeholder: "Pilih Tahun", allowEmptyOption: true });
    </script>
@endpush

@endsection
