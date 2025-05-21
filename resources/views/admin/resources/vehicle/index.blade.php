@extends('admin.template.template')
@section('title', 'Kendaraan')

@section('content')
@push('css')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/2.2.1/css/dataTables.bootstrap5.css">
<style>
.vehicle-photo {
    width: 100%;       
    height: auto;
    max-width: 150px;
    object-fit: cover;
    border-radius: 4px;
}

@media (max-width: 768px) {
    table.responsive-table thead {
        display: none;
    }

    table.responsive-table, 
    table.responsive-table tbody, 
    table.responsive-table tr, 
    table.responsive-table td {
        display: block;
        width: 100%;
    }

    table.responsive-table tr {
        margin-bottom: 1rem;
        border: 1px solid #dee2e6;
        border-radius: 5px;
        padding: 0.5rem;
        background-color: #f8f9fa;
    }

    table.responsive-table td {
        text-align: left;
        padding-left: 1rem;
        padding-right: 1rem;
        position: relative;
        font-size: 14px;
    }

    table.responsive-table td::before {
        content: attr(data-label);
        font-weight: bold;
        display: block;
        margin-bottom: 0.25rem;
    }

    .btn {
        margin: 0.25rem 0;
        font-size: 14px;
        padding: 0.375rem 0.75rem;
    }
}
</style>

@endpush

<div class="app-content-header">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6"><h3 class="mb-0">Data Kendaraan</h3></div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                    <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item">Setting</li>
                    <li class="breadcrumb-item active" aria-current="page">Kendaraan</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="app-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card mb-4">
                    <div class="card-header"><h3 class="card-title">Kendaraan</h3></div>

                    <div class="col-12 d-flex">
                        @if(auth()->user()->hasRole(['admin', 'supervisor']))
                            <a href="{{ url('setting/vehicle/create') }}" class="btn btn-primary ms-3 mt-3">Tambah</a>
                            <a href="{{ url('setting/vehicle/invoice') }}" class="btn btn-warning ms-3 mt-3">Invoice</a>
                        @endif
                    </div>

                    <div class="card-body">
                        <table id="dataTableVehicle" class="table table-striped responsive-table" style="width:100%">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Foto Kendaraan</th>
                                    <th>Nama Kendaraan</th>
                                    <th>Merk</th>
                                    <th>Plat Nomer</th>
                                    <th>Warna</th>
                                    <th>Tahun</th>
                                    <th>Cabang</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($vehicle as $v)
                                    <tr>
                                        <td data-label="No">{{ $loop->iteration }}</td>
                                        <td data-label="Foto Kendaraan">
                                            @if($v->photo)
                                                <img src="{{ asset('storage/'.$v->photo) }}" alt="Foto Kendaraan" class="img-fluid vehicle-photo">
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td data-label="Nama Kendaraan">{{ $v->name }}</td>
                                        <td data-label="Merk">{{ $v->brand?->name ?? '-' }}</td>
                                        <td data-label="Plat Nomer">{{ $v->plate_number }}</td>
                                        <td data-label="Warna">{{ $v->color }}</td>
                                        <td data-label="Tahun" class="text-start">{{ $v->year }}</td>

                                        <td data-label="Cabang">{{ $v->branch?->email ?? '-' }}</td>
                                        <td data-label="Aksi">
                                            @if(auth()->user()->hasRole(['admin', 'supervisor']))
                                            <a href="{{ url('setting/vehicle/edit/' . $v->vehicleId) }}" class="btn btn-primary">Edit</a>
                                            <a href="{{ url('setting/vehicle/show/' . $v->vehicleId) }}" class="btn btn-primary">Detail</a>
                                            <form action="{{ url('setting/vehicle/delete', $v->vehicleId) }}" method="POST" style="display: inline;">
                                                    @csrf
                                                    @method('POST') 
                                                    <button type="submit" class="btn btn-danger text-light hover:text-red-700" onclick="return confirm('Are you sure?')">Hapus</button>
                                                </form>        
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

@push('js')
<script src="https://code.jquery.com/jquery-3.7.1.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/2.2.1/js/dataTables.js"></script>
<script src="https://cdn.datatables.net/2.2.1/js/dataTables.bootstrap5.js"></script>
<script>
    new DataTable('#dataTableVehicle');
</script>
@endpush

@endsection
