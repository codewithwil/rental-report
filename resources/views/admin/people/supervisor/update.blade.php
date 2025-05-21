@extends('admin.template.template')
@section('title', 'edit akun user')
@section('content')
<div class="app-content-header">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6"><h3 class="mb-0">Edit Data Supervisor</h3></div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                    <li class="breadcrumb-item"><a href="{{url('/dashboard')}}">Dashboard</a></li>
                    <li class="breadcrumb-item">Konfigurasi</li>
                    <li class="breadcrumb-item active" aria-current="page">Users</li>
                </ol>
            </div>
        </div>
    </div>
</div>
<div class="app-content">
    <div class="container-fluid">
        <div class="row">
            <form class="row g-3" action="{{ url('people/supervisor/update/'.$users->supervisorId) }}" method="POST" enctype="multipart/form-data">
                @csrf
             
                <div class="card mb-4" style="border-left: 5px solid #007bff;">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">Akun Supervisor</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="emailUser" class="form-label">Email</label>
                                    <input type="email" name="email" class="form-control" id="emailUser" value="{{ $users->user->email }}" placeholder="Masukkan Email">
                                </div>
                                <div class="mb-3">
                                    <label for="role" class="form-label">Role</label>
                                    @if(auth()->user()->hasRole(['admin']))
                                    <select name="role" id="role" class="form-control">
                                        <option value="">Pilih Role</option>
                                        @foreach ($roles as $role)
                                            <option value="{{ $role->name }}" {{ $userRole === $role->name ? 'selected' : '' }}>
                                                {{ ucfirst($role->name) }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @else
                                    <select name="role" id="role" class="form-control" disabled>
                                        @foreach ($roles as $role)
                                        <option value="{{ $role->name }}" {{ $userRole === $role->name ? 'selected' : '' }}>
                                            {{ ucfirst($role->name) }}
                                        </option>
                                    @endforeach
                                    </select>
                                    @endif
                                </div>
                                <div class="mb-3">
                                    <label for="branch" class="form-label">Cabang</label>
                                    <select name="branch_id" class="form-control" id="branch">
                                        <option value="">--- Pilih Cabang ---</option>
                                        @if (Auth::user()->branch)
                                            <option value="{{ Auth::user()->branch->branchId }}" selected>
                                                {{ Auth::user()->branch->address }}
                                            </option>
                                        @else
                                            @foreach ($branch as $b)
                                                <option value="{{ $b->branchId }}" 
                                                    {{ $users->user && $b->branchId == $users->user->branch_id ? 'selected' : '' }}>
                                                    {{ $b->email }}
                                                </option>
                                            @endforeach
                                        @endif
                                        </select>
                                </div>              
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="foto" class="form-label">Foto</label>
                                    @if ($users->foto)
                                        <div class="mb-2">
                                            <img src="{{ asset('storage/' . $users->foto) }}" 
                                                 alt="Foto Admin" 
                                                 class="rounded-circle" 
                                                 style="width: 100px; height: 100px; object-fit: cover;">
                                        </div>
                                    @endif
                                    <input type="file" name="foto" class="form-control" id="foto">
                                </div>                                  
                                <div class="mb-3">
                                    <label for="passwordUser" class="form-label">Password</label>
                                    <input type="password" name="password" class="form-control" id="passwordUser"  placeholder="Masukkan password">
                                </div>
                            </div>
                        </div>
                        
                    </div>
                
                    <!-- Data Supervisor Section -->
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">Data Supervisor</h5>
                    </div>
                    <div class="card-body">
                        <!-- Nama Data user Email -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="nameUser" class="form-label">Nama</label>
                                    <input type="text" name="name" class="form-control" id="nameUser" value="{{ $users->name }}"  placeholder="Masukkan nama user">
                                </div>
                             
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="telepon" class="form-label">Nomor Telepon</label>
                                    <input type="number"  name="telepon" class="form-control" id="telepon" value="{{ $users->telepon }}" placeholder="Masukkan nomor telepon">
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary">Simpan</button>
                            <a href="{{ url('people/supervisor') }}" class="btn btn-secondary">Kembali</a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>


@endsection