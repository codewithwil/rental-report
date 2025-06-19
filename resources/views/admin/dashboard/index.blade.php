@extends('admin.template.template')
@section('title', 'Dashboard')

@section('content')
<div class="app-content-header py-3 border-bottom mb-3">
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center flex-wrap">
            <h3 class="mb-2">Dashboard</h3>
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item active">Dashboard</li>
            </ol>
        </div>
    </div>
</div>

<div class="app-content">
    <div class="container-fluid">

        {{-- Statistik Card --}}
        <div class="row g-4 mb-4">
            <div class="col-md-4">
                <div class="card shadow-sm border-0 h-100 text-bg-success text-white">
                    <div class="card-body d-flex align-items-center justify-content-between">
                        <div>
                            <h1 class="fw-bold">{{ $branch }}</h1>
                            <p class="mb-0">Cabang</p>
                        </div>
                        <i class="bi bi-building fs-1 opacity-50"></i>
                    </div>
                    <div class="card-footer bg-transparent border-0">
                        <a href="{{ url('/setting/branch') }}" class="text-white text-decoration-none">More info <i class="bi bi-arrow-right"></i></a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card shadow-sm border-0 h-100 text-bg-primary text-white">
                    <div class="card-body d-flex align-items-center justify-content-between">
                        <div>
                            <h1 class="fw-bold">{{ $vehicle }}</h1>
                            <p class="mb-0">Kendaraan</p>
                        </div>
                        <i class="bi bi-truck fs-1 opacity-50"></i>
                    </div>
                    <div class="card-footer bg-transparent border-0">
                        <a href="{{ url('/setting/vehicle') }}" class="text-white text-decoration-none">More info <i class="bi bi-arrow-right"></i></a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card shadow-sm border-0 h-100 text-bg-warning text-dark">
                    <div class="card-body d-flex align-items-center justify-content-between">
                        <div>
                            <h1 class="fw-bold">{{ $users }}</h1>
                            <p class="mb-0">Users</p>
                        </div>
                        <i class="bi bi-people fs-1 opacity-50"></i>
                    </div>
                    <div class="card-footer bg-transparent border-0">
                        <a href="/people/users" class="text-dark text-decoration-none">More info <i class="bi bi-arrow-right"></i></a>
                    </div>
                </div>
            </div>
        </div>

        {{-- Grafik Laporan --}}
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-white">
                <h5 class="mb-0">Grafik Laporan Mingguan</h5>
            </div>
            <div class="card-body">
                <canvas id="statusChart" height="120"></canvas>
            </div>
        </div>

    </div>
</div>

{{-- Modal Aturan Perusahaan --}}
<div class="modal fade" id="rulesModal" tabindex="-1" aria-labelledby="rulesModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Aturan Perusahaan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Selamat datang di dashboard. Berikut beberapa aturan perusahaan yang wajib dipatuhi:</p>
                @foreach (explode("\n", $rules->content) as $line)
                    <p>{{ $line }}</p>
                @endforeach
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Saya Mengerti</button>
            </div>
        </div>
    </div>
</div>

{{-- Script --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const ctx = document.getElementById('statusChart').getContext('2d');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: @json($chartDates),
            datasets: [
                {
                    label: 'Perlu Validasi',
                    data: @json(collect($chartData)->pluck('pending')),
                    borderColor: 'rgba(255, 206, 86, 1)',
                    backgroundColor: 'rgba(255, 206, 86, 0.2)',
                    tension: 0.4,
                    fill: true
                },
                {
                    label: 'Disetujui',
                    data: @json(collect($chartData)->pluck('approve')),
                    borderColor: 'rgba(75, 192, 192, 1)',
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    tension: 0.4,
                    fill: true
                },
                {
                    label: 'Ditolak',
                    data: @json(collect($chartData)->pluck('rejected')),
                    borderColor: 'rgba(255, 99, 132, 1)',
                    backgroundColor: 'rgba(255, 99, 132, 0.2)',
                    tension: 0.4,
                    fill: true
                },
            ]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'top',
                }
            },
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });

    const rulesModal = new bootstrap.Modal(document.getElementById('rulesModal'));
    rulesModal.show();
});
</script>
@endsection
