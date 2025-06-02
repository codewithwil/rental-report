@extends('admin.template.template')
@section('title', 'Tambah Laporan Mingguan')

@push('css')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bs-stepper@1.7.0/dist/css/bs-stepper.min.css" rel="stylesheet">
<link href="https://unpkg.com/filepond/dist/filepond.min.css" rel="stylesheet" />
<link href="https://unpkg.com/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.min.css" rel="stylesheet" />

<style>
    .preview-img {
        max-height: 150px;
        margin-top: 10px;
        object-fit: contain;
    }
    .bs-stepper-header {
        display: flex;
        flex-wrap: wrap;
        justify-content: space-between;
        border-bottom: 2px solid #dee2e6;
        margin-bottom: 1rem;
        position: relative;
    }
    .bs-stepper-header .step {
        position: relative;
        flex: 1 1 0;
        text-align: center;
        min-width: 100px;
    }
    .bs-stepper .content {
        opacity: 0;
        transform: translateY(20px);
        transition: opacity 0.4s ease, transform 0.4s ease;
        position: relative;
        width: 100%;
        pointer-events: none;
        visibility: hidden;
        height: 0;
        overflow: hidden;
    }
    .bs-stepper .content.active {
        opacity: 1;
        transform: translateY(0);
        pointer-events: auto;
        visibility: visible;
        height: auto;
        overflow: visible;
    }
    .bs-stepper-header .step::before {
        content: "";
        position: absolute;
        top: 50%;
        left: 0;
        right: 0;
        height: 2px;
        background-color: #ccc;
        z-index: 0;
        transform: translateY(-50%);
    }
    .bs-stepper-header .step:first-child::before {
        left: 50%;
    }
    .bs-stepper-header .step:last-child::before {
        right: 50%;
    }
    .bs-stepper-header .step .step-trigger {
        position: relative;
        z-index: 1;
        background: white;
        border: none;
        outline: none;
        padding: 0;
        cursor: pointer;
    }
    .bs-stepper-header .bs-stepper-circle {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background-color: #0d6efd;
        color: white;
        font-weight: bold;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 5px auto;
        font-size: 1rem;
    }
    .bs-stepper-header .step.active .bs-stepper-circle {
        background-color: #198754;
    }
    .bs-stepper-label {
        display: block;
        font-size: 0.85rem;
        color: #333;
    }
    @media (max-width: 576px) {
        .bs-stepper-header {
            flex-direction: row;  
            flex-wrap: wrap;     
            gap: 0.5rem;         
            overflow-x: auto;    
            -webkit-overflow-scrolling: touch; 
            padding-bottom: 0.5rem; 
        }
        .bs-stepper-header .step {
            flex: 0 0 auto; 
            min-width: 70px; 
        }
        .bs-stepper-header .step::before {
            display: none; 
        }
    }
    .filepond--root {
        height: 180px;
        min-height: 180px;
        font-size: 1rem;
    }
    .filepond--drop-label {
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 1rem;
    }

    .filepond--panel-root {
        border: 2px dashed #007bff;
        border-radius: 8px;
        background-color: #f8f9fa;
    }
</style>
@endpush

@section('content')
<div class="app-content-header">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6"><h3 class="mb-0">Tambah Laporan Mingguan</h3></div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                    <li class="breadcrumb-item"><a href="{{url('/dashboard')}}">Dashboard</a></li>
                    <li class="breadcrumb-item">Laporan</li>
                    <li class="breadcrumb-item active" aria-current="page">Laporan Mingguan</li>
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
                    <h5 class="mb-0">Laporan Mingguan</h5>
                </div>
                <div class="card-body">
                    <form action="{{ url('report/weeklyReport/store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div id="stepper" class="bs-stepper">
                            <div class="bs-stepper-header" role="tablist">
                                @php
                                    $steps = ['Informasi','Body', 'Lampu', 'Ban', 'Wiper', 'Mesin', 'Kelengkapan', 'Catatan'];
                                @endphp
                                @foreach($steps as $index => $step)
                                    <div class="step" data-target="#step-{{ $index + 1 }}">
                                        <button type="button" class="step-trigger" role="tab" id="steppertrigger{{ $index + 1 }}">
                                            <span class="bs-stepper-circle">{{ $index + 1 }}</span>
                                            <span class="bs-stepper-label">{{ $step }}</span>
                                        </button>
                                    </div>
                                @endforeach
                            </div>
                            <div class="bs-stepper-content pt-4">
                                @php
                                    $stepFields = [
                                        ['key' => 'body', 'title' => 'Foto Body Mobil', 'positions' => ['Depan Kanan', 'Depan Kiri', 'Belakang Kanan', 'Belakang Kiri']],
                                        ['key' => 'light', 'title' => 'Foto Lampu Mobil', 'positions' => ['Depan Kanan', 'Depan Kiri', 'Belakang Kanan', 'Belakang Kiri']],
                                        ['key' => 'tire', 'title' => 'Foto Ban Mobil', 'positions' => ['Depan Kanan', 'Depan Kiri', 'Belakang Kanan', 'Belakang Kiri']],
                                        ['key' => 'wiper', 'title' => 'Foto Wiper', 'positions' => ['Depan Kanan', 'Depan Kiri', 'Belakang']],
                                        ['key' => 'engine', 'title' => 'Mesin & Radiator', 'positions' => ['Mesin', 'Radiator']],
                                        ['key' => 'equipment', 'title' => 'Kelengkapan Mobil', 'positions' => ['AC Depan','Buku Servis','Radio','Speedometer','Klakson','Dongkrak','Segitiga','Ban Serep', 'BPKB Mobil', 'STNK Mobil', 'KIR Kendaraan']],
                                    ];
                                @endphp

                                <div id="step-1" class="content active">
                                    <h5>Informasi Laporan</h5>
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Kendaraan</label>
                                            <select id="VehicleSelect" name="vehicle_id" class="form-control" placeholder="Pilih Kendaraan" autocomplete="off">
                                                <option value="">-- Pilih Kendaraan --</option>
                                                @foreach($vehicle as $item)
                                                    <option value="{{ $item->vehicleId }}">{{ $item->name }} - {{ $item->plate_number }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="report_date" class="form-label">Tanggal Laporan</label>
                                            <input type="date" class="form-control" id="report_date" name="report_date" value="{{ date('Y-m-d') }}" required>
                                        </div>
                                    </div>
                                    <div class="d-flex justify-content-end mt-3">
                                        <button class="btn btn-primary" type="button" onclick="stepper.next()">Lanjut</button>
                                    </div>
                                </div>
                                @foreach($stepFields as $i => $step)
                                    <div id="step-{{ $i + 2 }}" class="content">
                                        <h5>{{ $step['title'] }}</h5>
                                        <div class="row">
                                            @foreach($step['positions'] as $pos)
                                            <div class="col-md-6 mb-3">
                                               <div class="custom-file-upload">
                                                   @php
                                                        $isVideo = $step['key'] === 'equipment' && in_array($pos, ['Radio', 'Klakson', 'Speedometer']);
                                                        $labelType = $isVideo ? 'Video' : 'Foto';
                                                    @endphp
                                                    <label class="form-label">Upload {{ $labelType }} {{ $pos }}</label>
                                                    <input
                                                        type="file"
                                                        name="details[{{ $step['key'] }}][{{ $pos }}]"
                                                        class="filepond"
                                                        accept="{{ $step['key'] === 'engine' || $step['key'] === 'equipment' ? 'image/*,video/*' : 'image/*' }}"
                                                    />
                                                </div>
                                            </div>
                                            @endforeach
                                        </div>
                                        <div class="d-flex justify-content-between mt-3">
                                            <button class="btn btn-secondary" type="button" onclick="goPrevious()">Kembali</button>
                                            <button class="btn btn-primary ms-auto" type="button" onclick="goNext()">Lanjut</button>
                                        </div>
                                    </div>
                                @endforeach

                                <div id="step-{{ count($stepFields) + 2 }}" class="content">
                                    <div class="mb-3">
                                        <label>Catatan</label>
                                        <textarea name="note" class="form-control" rows="4"></textarea>
                                    </div>
                                    <div class="d-flex justify-content-between">
                                        <button class="btn btn-secondary" type="button" onclick="stepper.previous()">Kembali</button>
                                        <button type="submit" class="btn btn-success">Simpan Laporan</button>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('js')
<script src="https://unpkg.com/filepond/dist/filepond.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bs-stepper@1.7.0/dist/js/bs-stepper.min.js"></script>
<script src="https://unpkg.com/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.min.js"></script>
<script src="https://unpkg.com/filepond-plugin-file-encode/dist/filepond-plugin-file-encode.min.js"></script>

<script>
    let stepper;
    document.addEventListener('DOMContentLoaded', function () {
        stepper = new window.Stepper(document.querySelector('#stepper'));
        stepper.to(1);

        updateStepperVisuals(stepper._currentIndex);

        document.querySelector('#stepper').addEventListener('show.bs-stepper', function (event) {
            updateStepperVisuals(event.detail.indexStep);
        });

        function updateFileNameAndPreview() {
            document.querySelectorAll('.custom-file-upload input[type="file"]').forEach(input => {
                input.addEventListener('change', function(e) {
                    const file = this.files[0];
                    const fileNameSpan = this.closest('.custom-file-upload').querySelector('.file-name');
                    const previewImg = this.closest('.custom-file-upload').querySelector('.preview-img');

                    if (file) {
                        fileNameSpan.textContent = file.name;
                        if (file.type.startsWith('image/')) {
                            const reader = new FileReader();
                            reader.onload = function(e) {
                                previewImg.src = e.target.result;
                                previewImg.classList.remove('d-none');
                            }
                            reader.readAsDataURL(file);
                        } else {
                            previewImg.src = '';
                            previewImg.classList.add('d-none');
                        }
                    } else {
                        fileNameSpan.textContent = '';
                        previewImg.src = '';
                        previewImg.classList.add('d-none');
                    }
                });
            });
        }

        updateFileNameAndPreview();
        FilePond.registerPlugin(
            FilePondPluginImagePreview,
            FilePondPluginFileEncode
        );

        document.querySelectorAll('input.filepond').forEach(input => {  
            FilePond.create(input, {
                allowMultiple: false,
                allowFileEncode: true,
                instantUpload: false,
                labelIdle: 'Drag & Drop your file or <span class="filepond--label-action">Browse</span>',
                acceptedFileTypes: ['image/*', 'video/*'],
            });
        });
    });

    function goPrevious() {
        if (stepper) {
            stepper.previous();
            updateStepperVisuals();
        }
    }

    function goNext() {
        if (stepper) {
            stepper.next();
            updateStepperVisuals();
        }
    }

    function updateStepperVisuals() {
        const steps = document.querySelectorAll('.bs-stepper-header .step');
        const contents = document.querySelectorAll('.bs-stepper .content');
        const currentIndex = stepper._currentIndex;

        steps.forEach((step, index) => {
            step.classList.toggle('active', index === currentIndex);
        });

        contents.forEach((content, index) => {
            content.classList.toggle('active', index === currentIndex);
        });
    }

</script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        new TomSelect("#VehicleSelect", {
            placeholder: "Pilih Kendaraan",
            maxItems: 1,
            plugins: ['remove_button'],
            closeAfterSelect: true,
        });
    });
</script>
@endpush
