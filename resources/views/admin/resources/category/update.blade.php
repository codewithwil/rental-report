@extends('admin.template.template')
@section('title', 'edit data kategori')
@section('content')
<div class="app-content-header">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6"><h3 class="mb-0">Tambah Data Kategori</h3></div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                    <li class="breadcrumb-item"><a href="{{url('/dashboard')}}">Dashboard</a></li>
                    <li class="breadcrumb-item">Setting</li>
                    <li class="breadcrumb-item active" aria-current="page">Kategori</li>
                </ol>
            </div>
        </div>
    </div>
</div>
<div class="app-content">
    <div class="container-fluid">
        <div class="row">
            <form class="row g-3" action="{{ url('setting/category/update/'.$category->categoryId) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="card mb-4" style="border-left: 5px solid #007bff;">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">Data Kategori</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label for="nameCategory" class="form-label">Nama Kategori</label>
                                    <input type="text" name="name" class="form-control" id="nameCategory" value="{{ $category->name }}"  placeholder="Masukkan nama kategori">
                                </div>
                                 <div class="mb-3">
                                    <label for="type" class="form-label">Tipe Kendaraan</label>
                                    <select name="type" id="type" class="form-control">
                                        <option value="">-- Pilih Tipe Kendaraan --</option>
                                        <option value="1" {{ old('type', $category->type ?? '') == '1' ? 'selected' : '' }}>Mobil</option>
                                        <option value="2" {{ old('type', $category->type ?? '') == '2' ? 'selected' : '' }}>Motor</option>
                                    </select>  
                                </div>
                            </div>
                        </div>
                        
                    </div>
                </div>
                <!-- Tombol Submit -->
                <div class="col-12">
                    <button type="submit" class="btn btn-primary">Simpan</button>
                    <button type="reset" class="btn btn-warning">Reset Form</button>
                    <a href="{{ url('setting/category') }}" class="btn btn-secondary">Kembali</a>
                </div>
            </form>
        </div>
    </div>
</div>


@endsection