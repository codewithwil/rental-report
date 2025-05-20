@extends('admin.template.template')
@section('title', 'edit data Merek Kendaraan')
@section('content')
<div class="app-content-header">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6"><h3 class="mb-0">Tambah Data Merek Kendaraan</h3></div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                    <li class="breadcrumb-item"><a href="/dashboard">Dashboard</a></li>
                    <li class="breadcrumb-item">Setting</li>
                    <li class="breadcrumb-item active" aria-current="page">Merek Kendaraan</li>
                </ol>
            </div>
        </div>
    </div>
</div>
<div class="app-content">
    <div class="container-fluid">
        <div class="row">
            <form class="row g-3" action="{{ url('setting/brand/update/'.$brand->brandId) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="card mb-4" style="border-left: 5px solid #007bff;">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">Data Merek Kendaraan</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label for="brandName" class="form-label">Nama Merek Kendaraan</label>
                                    <input type="text" name="name" class="form-control" id="brandName" value="{{ $brand->name }}"  placeholder="Masukkan nama Merek Kendaraan">
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary">Simpan</button>
                            <a href="{{url('/setting/brand')}}" class="btn btn-warning">Kembali</a>
                        </div>
                    </div>    
                </div>
            </form>
        </div>
    </div>
</div>


@endsection