@extends('admin.template.template')
@section('title', 'Users')
@section('content')
@push('css')
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
            <div class="col-sm-6"><h3 class="mb-0">Informasi Seluruh User</h3></div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                    <li class="breadcrumb-item"><a href="{{url('/dashboard')}}">Dashboard</a></li>
                    <li class="breadcrumb-item">Konfigurasi</li>
                    <li class="breadcrumb-item active" aria-current="page">All Users</li>
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
                    <div class="card-header"><h3 class="card-title">Users</h3></div>                    
                    <div class="card-body">
                        <table id="dataTableUsers" class="table table-striped responsive-table" style="width:100%">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Email</th>
                                    <th>IP Terakhir login</th>
                                    <th>Device Terakhir login</th>
                                    <th>Terakhir Aktif</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($users as $us)
                                <tr>
                                    <td data-label="No">{{ $loop->iteration  }}</td>                                    
                                    <td data-label="Email">{{ $us->email  }}</td>
                                    <td data-label="Ip Terakhir Login">{{ $us->last_login_ip ?? 'belum login' }}</td>
                                    <td data-label="Device Terakhir login">{{ $us->last_login_device ?? 'belum login' }}</td>
                                    <td data-label="Terakhir Aktif">
                                        {{ $us->last_active_at ? \Carbon\Carbon::parse($us->last_active_at)->diffForHumans() : 'belum pernah login' }}
                                    </td>
                                    <td data-label="Status">
                                    @php
                                        $lastActive = \Carbon\Carbon::parse($us->last_active_at);
                                        $now = \Carbon\Carbon::now();
                                        $diffMinutes = $lastActive->diffInMinutes($now);
                                    @endphp

                                    @if($us->last_active_at && $diffMinutes)
                                        <span class="text-success">Online</span>
                                    @else
                                        <span class="text-muted">
                                            Offline
                                        </span>
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
        new DataTable('#dataTableUsers');
    </script>
@endpush
@endsection