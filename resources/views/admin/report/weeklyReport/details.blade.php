@extends('admin.template.template')
@section('title', 'Detail Laporan Mingguan')

@section('content')
@push('css')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css">
@endpush

<div class="app-content-header">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6"><h3 class="mb-0">Detail Laporan: {{ $weeklyReport->vehicle->name }}</h3></div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                    <li class="breadcrumb-item"><a href="/dashboard">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ url('report/weeklyReport') }}">Laporan Mingguan</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ $weeklyReport->vehicle->name }}</li>
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
                    <div class="card-header"><h3 class="card-title">Informasi Laporan</h3></div>
                    <div class="card-body">
                        <table class="table table-bordered">
                            <tr>
                                <th>Tanggal Laporan</th>
                                <td>{{ $weeklyReport->report_date }}</td>
                            </tr>
                            <tr>
                                <th>Foto Kendaraan</th>
                                <td>
                                    @if($weeklyReport->vehicle->photo)
                                        <img src="{{ asset('storage/'.$weeklyReport->vehicle->photo) }}"
                                            alt="Foto Kendaraan"
                                            style="max-height: 120px; cursor: pointer;"
                                            class="img-thumbnail"
                                            data-bs-toggle="modal"
                                            data-bs-target="#imageModal"
                                            data-bs-image="{{ asset('storage/'.$weeklyReport->vehicle->photo) }}">
                                    @else
                                        <em>Belum ada foto</em>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th>Nama Kendaraan</th>
                                <td>{{ $weeklyReport->vehicle->name ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th>Plat Nomor</th>
                                <td>{{ $weeklyReport->vehicle->plate_number ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th>Warna</th>
                                <td>{{ $weeklyReport->vehicle->color ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th>Cabang</th>
                                <td>{{ $weeklyReport->vehicle->branch->email ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th>Petugas</th>
                               <td>{{ $weeklyReport->user?->admin->name ?? $weeklyReport->user?->supervisor?->name ?? $weeklyReport->user?->employee?->name ?? 'No PIC' }}</td>
                            </tr>
                            <tr>
                                <th>Status</th>
                                <td>{{ $weeklyReport->status_label }}</td>
                            </tr>
                            <tr>
                                <th>Catatan</th>
                                <td>{{ $weeklyReport->note ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th>Dibuat Pada</th>
                                <td>{{ \Carbon\Carbon::parse($weeklyReport->created_at)->format('d/m/Y') }}</td>
                            </tr>
                            <tr>
                                <th>Diperbarui Pada</th>
                                <td>{{ \Carbon\Carbon::parse($weeklyReport->updated_at)->format('d/m/Y') }}</td>
                            </tr>
                        </table>
                    </div>
                </div>

                <div class="card mb-4">
                    <div class="card-header"><h3 class="card-title">Detail Komponen Laporan</h3></div>
                    <div class="card-body">
                        <div class="accordion" id="accordionComponents">
                            @foreach($weeklyReport->weeklyReportDetail ?? [] as $index => $detail)
                                @php $uniqueId = 'component-' . $loop->index; @endphp
                                <div class="accordion-item mb-2">
                                    <h2 class="accordion-header" id="heading-{{ $uniqueId }}">
                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-{{ $uniqueId }}" aria-expanded="false" aria-controls="collapse-{{ $uniqueId }}">
                                            Komponen: {{ ucfirst($detail->component) }} | Posisi: {{ $detail->position }}
                                        </button>
                                    </h2>
                                    <div id="collapse-{{ $uniqueId }}" class="accordion-collapse collapse" aria-labelledby="heading-{{ $uniqueId }}" data-bs-parent="#accordionComponents">
                                        <div class="accordion-body">
                                            <p><strong>Komponen:</strong> {{ ucfirst($detail->component) }}</p>
                                            <p><strong>Posisi:</strong> {{ $detail->position }}</p>
                                            <p><strong>Jenis File:</strong> {{ $detail->file_type }}</p>
                                            <p><strong>Preview:</strong></p>
                                           @if(Str::startsWith($detail->file_type, 'image'))
                                                <img src="{{ asset('storage/' . $detail->file_path) }}" 
                                                    alt="preview" 
                                                    class="img-thumbnail preview-image" 
                                                    style="max-height: 150px; cursor: pointer;"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#imageModal"
                                                    data-bs-image="{{ asset('storage/' . $detail->file_path) }}">
                                            @elseif(Str::startsWith($detail->file_type, 'video'))
                                                <video controls style="max-height: 200px;">
                                                    <source src="{{ asset('storage/' . $detail->file_path) }}" type="{{ $detail->file_type }}">
                                                    Browser tidak mendukung video.
                                                </video>
                                            @else
                                                <p>Tidak dapat menampilkan file.</p>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                <div class="d-flex gap-2 mt-3">
                    <a href="{{ url('/report/weeklyReport') }}" class="btn btn-primary">Kembali</a>
                    @if($weeklyReport->status === \App\Models\Report\WeeklyReport\WeeklyReport::STATUS_PENDING)
                        <form action="{{ url('report/weeklyReport/approve/' . $weeklyReport->weekReportId) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-success">Setujui</button>
                        </form>
                        <button class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#rejectModal">Tolak</button>
                    @endif
                </div>  
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="rejectModal" tabindex="-1" aria-labelledby="rejectModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form action="{{ url('report/weeklyReport/reject/' . $weeklyReport->id) }}" method="POST">
        @csrf
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="rejectModalLabel">Tolak Laporan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <div class="modal-body">
                <div class="form-group mb-2">
                    <label for="note">Alasan Penolakan</label>
                    <textarea name="note" id="note" class="form-control" required></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-danger">Kirim Penolakan</button>
            </div>
        </div>
    </form>
  </div>
</div>

<div class="modal fade" id="imageModal" tabindex="-1" aria-labelledby="imageModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
      <div class="modal-body p-0">
        <img src="" id="imageModalSrc" class="img-fluid w-100" alt="Preview Besar">
      </div>
    </div>
  </div>
</div>

@push('js')
<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
<script>
    const imageModal = document.getElementById('imageModal');
    imageModal.addEventListener('show.bs.modal', event => {
        const button = event.relatedTarget;
        const imageSrc = button.getAttribute('data-bs-image');
        const modalImage = document.getElementById('imageModalSrc');
        modalImage.src = imageSrc;
    });
</script>
@endpush
@endsection
