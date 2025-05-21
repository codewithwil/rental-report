@extends('admin.template.template')
@section('title', 'Tambah Data Merek Kendaraan')
@section('content')

<div class="app-content-header">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6"><h3 class="mb-0">Tambah Data Merek Kendaraan</h3></div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                    <li class="breadcrumb-item"><a href="{{url('/dashboard')}}">Dashboard</a></li>
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
            <div class="col-md-12">
                <div class="card mb-4" style="border-left: 5px solid #007bff;">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">Tambah Data Merek Kendaraan</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="brandName" class="form-label">Nama</label>
                            <input type="text" id="brandName" class="form-control" placeholder="Masukkan Merk Kendaraan" onchange="editMerkKendaraan(${item.id}, 'name', this.value)">
                        </div>
                        <button type="button" class="btn btn-success" onclick="tambahMerk()">Tambah</button>
                        <button type="reset" class="btn btn-warning">Reset Form</button>
                        <a href="{{ url('setting/brand') }}" class="btn btn-secondary">Kembali</a>
                    </div>
                </div>
            </div>

            <!-- Tabel sementara -->
            <div class="col-md-12">
                <div class="card shadow-lg border-0 rounded">
                    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                        <h5 class="mb-0"><i class="fas fa-list-alt me-2"></i>Daftar Merek Kendaraan</h5>
                    </div>
                    <div class="card-body p-3">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered align-middle text-center" id="brandTable">
                                <thead class="bg-dark text-white">
                                    <tr>
                                        <th style="width: 10%;">No</th>
                                        <th style="width: 60%;">Nama Merek Kendaraan</th>
                                        <th style="width: 30%;">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                        <button type="button" class="btn btn-md btn-success  fwbold mt-3 shadow-sm" onclick="simpanSemua()">
                            <i class="fas fa-save me-2"></i> Simpan Semua
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('js')
    <script>
        let brandList = [];

        function tambahMerk() {
            let brandName = document.getElementById('brandName').value.trim();

            if (brandName === '') {
                alert("Nama Merek Kendaraan tidak boleh kosong!");
                return;
            }

            let newData = { id: Date.now(), name: brandName };
            brandList.push(newData);
            document.getElementById('brandName').value = ''; 
            renderTable();
        }

        function renderTable() {
            let tbody = document.querySelector("#brandTable tbody");
            tbody.innerHTML = '';

            brandList.forEach((item, index) => {
                let row = `
                    <tr>
                        <td>${index + 1}</td>
                        <td><input type="text" class="form-control" value="${item.name}" onchange="editMerkKendaraan(${item.id}, this.value)"></td>
                        <td>
                            <button class="btn btn-danger btn-sm" onclick="hapusKategori(${item.id})">Hapus</button>
                        </td>
                    </tr>
                `;
                tbody.innerHTML += row;
            });
        }

        function editMerkKendaraan(id, field, value) {
            let kategori = brandList.find(item => item.id === id);
            if (kategori) {
                kategori[field] = value;
            }
        }


        function hapusKategori(id) {
            brandList = brandList.filter(item => item.id !== id);
            renderTable();
        }

     function simpanSemua() {
        if (brandList.length === 0) {
            toastr.warning("Tidak ada data untuk disimpan!", "Peringatan");
            return;
        }

        let requests = brandList.map(item => {
            let formData = new FormData();
            formData.append('name', item.name);

            return fetch("{{ url('setting/brand/store') }}", {
                method: "POST",
                headers: {
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                },
                body: formData
            }).then(response => response.json());
        });

        Promise.all(requests).then(() => {
            brandList = [];
            renderTable();
            toastr.success("Semua data berhasil disimpan!", "Success");
            setTimeout(() => {
                window.location.href = "/setting/brand/";
            }, 1500);
        }).catch(() => {
            toastr.error("Terjadi kesalahan saat menyimpan data!", "Error");
        });
    }


    </script>
@endpush
@endsection
