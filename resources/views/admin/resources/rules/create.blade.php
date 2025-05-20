@extends('admin.template.template')
@section('title', 'Tambah Data Peraturan Perusahaan')
@section('content')

<div class="app-content-header">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6"><h3 class="mb-0">Tambah Data Peraturan Perusahaan</h3></div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                    <li class="breadcrumb-item"><a href="/dashboard">Dashboard</a></li>
                    <li class="breadcrumb-item">Setting</li>
                    <li class="breadcrumb-item active" aria-current="page">Peraturan Perusahaan</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="app-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card mb-4" style="border-left: 5px solid #007bff;">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">Tambah Data Peraturan Perusahaan</h5>
                    </div>
                    <div class="card-body">
                        <form class="row g-3" action="{{ url('setting/rules/store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="mb-3">
                                <label for="content" class="form-label">Peraturan Perusahaan</label>
                                <textarea class="form-control" name="content" id="content" cols="30" rows="10" placeholder="Masukan Peraturan Perusahaan"></textarea>
                            </div>
                            <div class="col-12">
                            <button type="submit" class="btn btn-primary">Simpan</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

@endsection
