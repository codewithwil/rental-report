    @extends('admin.template.template')
    @section('title', 'edit Data Cabang')
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
                <div class="col-sm-6"><h3 class="mb-0">Edit Data Cabang</h3></div>
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
                <form class="row g-3" action="{{ url('setting/branch/update/'.$branch->branchId) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="card mb-4" style="border-left: 5px solid #007bff;">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0">Data Cabang</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="branchNameEdit" class="form-label">Nama Perusahaan</label>
                                        <select name="company_id" class="form-control" id="company_id" disabled>
                                            <option value="{{ $branch->company->companyId }}" selected>
                                                {{ $branch->company->name }}
                                            </option>
                                        </select>
                                        <input type="hidden" name="company_id" value="{{ $branch->company_id }}">
                                        
                                    </div>
                                    <div class="mb-3">
                                        <label for="emailEdit" class="form-label">Email Cabang</label>
                                        <input type="email" name="emailEdit" class="form-control" id="emailEdit" value="{{ $branch->email }}"  placeholder="Masukkan email cabang">
                                    </div>
                                    <div class="mb-3">
                                        <label for="phoneEdit" class="form-label">Nomor Telepon Cabang</label>
                                        <input type="number" name="phoneEdit" class="form-control" id="phoneEdit" value="{{ $branch->phone }}"  placeholder="Masukkan nomor telepon cabang">
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Pilih Lokasi di Peta</label>
                                        <div id="map"></div>
                                    </div>    
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Penanggung Jawab</label>
                                        <select id="picUser" name="user_id" class="form-control" autocomplete="off">
                                            <option value="">-- Pilih Penanggung Jawab --</option>
                                            @foreach($users as $item)
                                                <option value="{{ $item->id }}" {{ $branch->user_id == $item->id ? 'selected' : '' }}>
                                                    {{ $item->supervisor->name ?? $item->employee->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label for="addressEdit" class="form-label">Alamat Cabang</label>
                                        <textarea name="addressEdit" id="addressEdit" cols="30" rows="4" class="form-control">{{$branch->address}}</textarea>
                                    </div>
                                    <div class="mb-3">
                                        <label for="operationalHoursEdit" class="form-label">Jam Operasional</label>
                                        <input type="text" name="operationalHoursEdit" class="form-control" id="operationalHoursEdit" value="{{ $branch->operationalHours }}"  placeholder="Masukkan jam operasional cabang">
                                    </div>
                                    <div class="mb-3">
                                        <label for="ltdEdit" class="form-label">Latitude<small class="text-muted">*(Tidak Wajib)</small></label>
                                        <input type="text" name="ltdEdit" class="form-control" id="ltdEdit" value="{{ $branch->ltd }}">
                                    </div>
                                    <div class="mb-3">
                                        <label for="lngEdit" class="form-label">Longitude<small class="text-muted">*(Tidak Wajib)</small></label>
                                        <input type="text" name="lngEdit" class="form-control" id="lngEdit" value="{{ $branch->lng }}" >
                                    </div>
                                </div>
                            </div>  
                                    <!-- Tombol Submit -->
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary">Simpan</button>
                                <a href="{{ url('setting/branch') }}" class="btn btn-secondary">Kembali</a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>         
        </div>
    </div>

    @push('js')
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script src="https://unpkg.com/leaflet-geosearch@3.0.0/dist/bundle.min.js"></script>

    <script>
      document.addEventListener("DOMContentLoaded", function () {
            const defaultLat = {{ $branch->ltd ?? -6.200000 }};
            const defaultLng = {{ $branch->lng ?? 106.816666 }};
            const map = L.map('map').setView([defaultLat, defaultLng], 12);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© OpenStreetMap contributors'
            }).addTo(map);

            let marker = L.marker([defaultLat, defaultLng]).addTo(map);

            map.on('click', function (e) {
                const { lat, lng } = e.latlng;
                marker.setLatLng([lat, lng]);

                document.getElementById('ltdEdit').value = lat.toFixed(6);
                document.getElementById('lngEdit').value = lng.toFixed(6);
            });

            const map2 = L.map('map2').setView([defaultLat, defaultLng], 12);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© OpenStreetMap contributors'
            }).addTo(map2);

            let marker2 = L.marker([defaultLat, defaultLng]).addTo(map2);

            map2.on('click', function (e) {
                const { lat, lng } = e.latlng;
                marker2.setLatLng([lat, lng]);

                document.getElementById('ltd2').value = lat.toFixed(6);
                document.getElementById('lng2').value = lng.toFixed(6);
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

                document.getElementById('ltdEdit').value = ltd.toFixed(6);
                document.getElementById('lngEdit').value = lng.toFixed(6);
            });

            const search2 = new window.GeoSearch.GeoSearchControl({
                provider: new window.GeoSearch.OpenStreetMapProvider(),
                style: 'bar',
                autoComplete: true,
                autoCompleteDelay: 250,
                showMarker: false,
                retainZoomLevel: false,
                animateZoom: true,
                keepResult: true
            });
            map2.addControl(search2);

            map2.on('geosearch/showlocation', function(result) {
                const ltd = result.location.y;
                const lng = result.location.x;

                marker2.setLatLng([ltd, lng]);
                map2.setView([ltd, lng], 15);

                document.getElementById('ltd2').value = ltd.toFixed(6);
                document.getElementById('lng2').value = lng.toFixed(6);
            });
        });

    </script>

    <script>
        new TomSelect("#picUser", { placeholder: "Pilih Penanggung Jawab", allowEmptyOption: true });
    </script>
    @endpush
    @endsection