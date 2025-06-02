@extends('admin.template.template')
@section('title', 'Tambah Data Cabang')
@section('content')

@push('css')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<link rel="stylesheet" href="https://unpkg.com/leaflet-geosearch@3.0.0/dist/geosearch.css" />
<style>
    #map { height: 350px; width: 100%; margin-bottom: 20px; }
    .leaflet-control-geosearch {
        z-index: 1000;
        position: absolute;
        top: 10px;
        left: 10px;
    }
</style>
@endpush

<div class="app-content-header">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6"><h3 class="mb-0">Tambah Data Cabang</h3></div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                    <li class="breadcrumb-item"><a href="{{url('/dashboard')}}">Dashboard</a></li>
                    <li class="breadcrumb-item">Setting</li>
                    <li class="breadcrumb-item active" aria-current="page">Cabang</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="app-content">
    <div class="container-fluid">
        <div class="row">
            <div class="card mb-4" style="border-left: 5px solid #007bff;">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Data Cabang</h5>
                </div>
                <div class="card-body">
                    <form action="{{ url('setting/branch/store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="company_id" class="form-label">Cabang Pusat</label>
                                    <select name="company_id" class="form-control" id="company_id" disabled>
                                        <option value="{{ $company->companyId }}">{{ $company->name }}</option>
                                        <input type="hidden" name="company_id" value="{{$company->companyId}}">
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="email" class="form-label">Email Cabang</label>
                                    <input type="email" name="email" class="form-control" id="email"  placeholder="Masukkan email cabang">
                                </div>
                                <div class="mb-3">
                                    <label for="phone" class="form-label">Nomor Telepon Cabang</label>
                                    <input type="number" name="phone" class="form-control" id="phone" min="0" placeholder="Masukkan nomor telepon cabang">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Pilih Lokasi di Peta</label>
                                    <div id="map"></div>
                                </div>    
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="address" class="form-label">Alamat Cabang</label>
                                    <textarea name="address" id="address" cols="30" rows="4" class="form-control"></textarea>
                                </div>
                                <div class="mb-3">
                                    <label for="operationalHours" class="form-label">Jam Operasional</label>
                                    <input type="text" name="operationalHours" class="form-control" id="operationalHours"  placeholder="Masukkan jam operasional cabang">
                                </div>
                                <div class="mb-3">
                                    <label for="ltd" class="form-label">Latitude<small class="text-muted">*(Tidak Wajib)</small></label>
                                    <input type="text" name="ltd" class="form-control" id="ltd">
                                </div>
                                <div class="mb-3">
                                    <label for="lng" class="form-label">Longitude<small class="text-muted">*(Tidak Wajib)</small></label>
                                    <input type="text" name="lng" class="form-control" id="lng">
                                </div>
                            </div>
                        </div>    
                        <div class="col-12 mt-3">
                            <button type="submit" class="btn btn-primary">Simpan</button>
                            <button type="reset" class="btn btn-warning">Reset Form</button>
                            <a href="{{ url('setting/branch') }}" class="btn btn-secondary">Kembali</a>
                        </div>   
                    </form>  
                </div>
            </div>
        </div>
    </div>
</div>

@push('js')
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script src="https://unpkg.com/leaflet-geosearch@3.0.0/dist/bundle.min.js"></script>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const defaultLat = -6.200000;
            const defaultLng = 106.816666;

            const map = L.map('map').setView([defaultLat, defaultLng], 12);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© OpenStreetMap contributors'
            }).addTo(map);

            let marker = L.marker([defaultLat, defaultLng]).addTo(map);

            map.on('click', function (e) {
                const { lat, lng } = e.latlng;
                marker.setLatLng([lat, lng]);

                document.getElementById('ltd').value = lat.toFixed(6);
                document.getElementById('lng').value = lng.toFixed(6);
            });

            const search = new window.GeoSearch.GeoSearchControl({
                provider: new window.GeoSearch.OpenStreetMapProvider(),
                style: 'bar',
                autoComplete: true,
                autoCompleteDelay: 250,
                showMarker: false,
                retainZoomLevel: false,
                animateZoom: true,
                keepResult: true
            });
            map.addControl(search);

            map.on('geosearch/showlocation', function(result) {
                const ltd = result.location.y;
                const lng = result.location.x;

                marker.setLatLng([ltd, lng]);
                map.setView([ltd, lng], 15);

                document.getElementById('ltd').value = ltd.toFixed(6);
                document.getElementById('lng').value = lng.toFixed(6);
            });
        });
    </script>
@endpush

@endsection
