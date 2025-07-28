@extends('admin.template.template')
@section('title', 'Sewa Kendaraaan Rental')
@section('content')
@push('css')
<style>
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
            <div class="col-sm-6"><h3 class="mb-0">Data Sewa Kendaraaan Rental</h3></div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                    <li class="breadcrumb-item"><a href="{{url('/dashboard')}}">Dashboard</a></li>
                    <li class="breadcrumb-item">Transaksi</li>
                    <li class="breadcrumb-item active" aria-current="page">Sewa Kendaraan Rental</li>
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
                    <div class="card-header"><h3 class="card-title">Sewa Kendaraaan Rental</h3></div>
                    <div class="col-12 d-flex">
                        @if(auth()->user()->hasRole(['admin', 'supervisor']))
                        <a href="{{ url('transactions/rentCar/create') }}" class="btn btn-primary ms-3 mt-3">
                            Tambah
                        </a>
                        <a href="{{ url('transactions/rentCar/invoice') }}" class="btn btn-warning ms-3 mt-3">
                            Invoice
                        </a>
                        @endif

                    </div>
                    <div class="card-body">
                        <table id="dataTableRentCar" class="table table-striped responsive-table" style="width:100%">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Kendaraan</th>
                                    <th>Nama Penyewa</th>
                                    <th>Tanggal Awal Sewa</th>
                                    <th>Tanggal Sewa Berakhir</th>
                                    <th>Total Biaya Sewa</th>
                                    <th>Status Sewa</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($rentCar as $rent)
                                <tr>
                                    <td data-label="No">{{ $loop->iteration  }}</td>
                                    <td data-label="Kendaraan" class="text-start">{{ $rent->vehicle->name ?? '-' }} - {{ $rent->vehicle->plate_number ?? '-' }}</td>
                                    <td data-label="Nama Penyewa" class="text-start">{{ $rent->renter_name ?? 'nama penyewa tidak ditemukan' }}</td>
                                    <td data-label="Tanggal Awal Sewa">{{ $rent->startDate  ?? 'tanggal belum di atur'}}</td>
                                    <td data-label="Tanggal Sewa Berakhir">{{ $rent->endDate  ?? 'tanggal belum di atur'}}</td>
                                    <td data-label="Total Biaya Sewa" class="text-start">
                                        Rp {{ number_format($rent->paymentAmount->first()->amount ?? 0, 0, ',', '.') }}
                                    </td>
                                    <td data-label="Status Sewa">
                                        @php
                                            $statuses = ['Pending', 'Sedang Disewa', 'Selesai', 'Dibatalkan'];
                                        @endphp
                                        <span class="">{{ $statuses[$rent->status] ?? 'Unknown' }}</span>
                                    </td>
                                    <td data-label="Aksi">
                                        @if(auth()->user()->hasRole(['admin', 'supervisor']))
                                        <a href="{{ url('/transactions/rentCar/edit/' . $rent->rentCarId) }}" class="btn btn-primary">Edit</a>                                       
                                        <a href="{{ url('/transactions/rentCar/show/' . $rent->rentCarId) }}" class="btn btn-info">Detail</a>                                       
                                        <form id="delete-form-{{ $rent->rentCarId }}" action="{{ url('transactions/rentCar/delete', $rent->rentCarId) }}" method="POST" style="display: inline;">
                                                @csrf
                                                @method('POST')
                                                <button type="button" class="btn btn-danger btn-delete" data-id="{{ $rent->rentCarId }}">
                                                    Hapus
                                                </button>
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


@push('js')
    <script>
        new DataTable('#dataTableRentCar');
        document.querySelector('#dataTableRentCar tbody').addEventListener('click', function(event) {
            if(event.target.classList.contains('btn-delete')) {
                const id = event.target.getAttribute('data-id');
                
                Swal.fire({
                    title: 'Apakah kamu yakin?',
                    text: "Data Nota Perbaikan kendaraan akan dihapus permanen!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Ya, hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        document.getElementById('delete-form-' + id).submit();
                    }
                });
            }
        });
    </script>
@endpush
@endsection