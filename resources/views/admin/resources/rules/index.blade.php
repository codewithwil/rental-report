@extends('admin.template.template')
@section('title', 'Peraturan Perusahaan')
@section('content')

<div class="app-content-header">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6"><h3 class="mb-0">Data Peraturan Perusahaan</h3></div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                    <li class="breadcrumb-item"><a href="{{url('/dashboard')}}">Dashboard</a></li>
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
                <div class="card mb-4">
                    <div class="card-header"><h3 class="card-title">Peraturan Perusahaan</h3></div>
                    <div class="col-12 d-flex">
                        @if(auth()->user()->hasRole(['admin', 'supervisor']))
                            @if ($rules->count() == 0)
                                <a href="{{ url('setting/rules/create') }}" class="btn btn-primary ms-3 mt-3">
                                    Tambah
                                </a>
                            @endif
                        <a href="{{ url('setting/rules/invoice') }}" class="btn btn-warning ms-3 mt-3">
                            Invoice
                        </a>
                        @endif

                    </div>
                    <div class="card-body">
                        <table id="dataTableRules" class="table table-striped" style="width:100%">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Peraturan Perusahaan</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($rules as $rul)
                                <tr>
                                    <td>{{ $loop->iteration  }}</td>
                                     <td>
                                        @php
                                            $lines = explode("\n", $rul->content);
                                        @endphp
                                        @foreach ($lines as $line)
                                            <p>{{ $line }}</p>
                                        @endforeach
                                    </td>
                                    <td>
                                        @if(auth()->user()->hasRole(['admin', 'supervisor']))
                                            <a href="{{ url('/setting/rules/edit/' . $rul->rulesId) }}" class="btn btn-primary">Edit</a>                           
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
        new DataTable('#dataTableRules');
    </script>
@endpush
@endsection