@extends('admin.template.template')
@section('title', 'Dashboard')

@section('content')
<div class="app-content-header py-3 border-bottom mb-3">
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center flex-wrap">
            <h3 class="mb-0"><i class="bi bi-speedometer2 me-1"></i> Dashboard</h3>
            <ol class="breadcrumb mb-0 small">
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
            <div class="col-md-4" data-aos="fade-down" data-aos-delay="100">
                <div class="card shadow border-0 h-100 text-white" style="background: linear-gradient(135deg, #1d976c, #93f9b9);">
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
            <div class="col-md-4" data-aos="fade-down" data-aos-delay="200">
                <div class="card shadow border-0 h-100 text-white" style="background: linear-gradient(135deg, #396afc, #2948ff);">
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
            <div class="col-md-4" data-aos="fade-down" data-aos-delay="300">
                <div class="card shadow border-0 h-100 text-white" style="background: linear-gradient(135deg, #f7971e, #ffd200);">
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

        {{-- Grafik Keuangan --}}
        <div class="card border-0 shadow-sm rounded-4 mb-4" data-aos="fade-up" data-aos-delay="100">
            <div class="card-body py-3">
                <form method="GET" action="{{ url('/dashboard') }}">
                    <div class="row g-3 align-items-end">
                        <div class="col-md-auto">
                            <label for="filter_type" class="form-label small fw-semibold text-muted mb-1">
                                <i class="bi bi-filter me-1"></i>Jenis Filter
                            </label>
                            <select class="form-select form-select-sm" name="filter_type" id="filter_type">
                                <option value="month" {{ request('filter_type') === 'month' ? 'selected' : '' }}>Bulanan</option>
                                <option value="year" {{ request('filter_type') === 'year' ? 'selected' : '' }}>Tahunan</option>
                            </select>
                        </div>
                        <div class="col-md-auto" id="filter_value_container">
                            <label class="form-label small fw-semibold text-muted mb-1" for="filter_value">
                                <i class="bi bi-calendar me-1"></i>Pilih Periode
                            </label>

                            <input type="month" name="filter_value"
                                id="filter_value_month" class="form-control form-control-sm"
                                value="{{ request('filter_value') }}"
                                style="{{ request('filter_type') === 'year' ? 'display:none;' : '' }}">

                            <input type="number" 
                                name="filter_value"
                                id="filter_value_year"
                                placeholder="Tahun (misal: 2025)"
                                min="2000" max="{{ date('Y') }}"
                                class="form-control form-control-sm"
                                style="{{ request('filter_type') === 'year' ? '' : 'display:none;' }}">
                        </div>
                        <div class="col-md-auto">
                            <label class="d-block invisible">.</label>
                            <button type="submit" class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-funnel-fill me-1"></i> Terapkan
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        @if(request('filter_type') && request('filter_value'))
            <div class="mb-3">
                <p class="text-muted">
                    Menampilkan data untuk: 
                    <strong>
                        @if(request('filter_type') === 'year')
                            Tahun {{ request('filter_value') }}
                        @else
                            {{ \Carbon\Carbon::parse(request('filter_value'))->translatedFormat('F Y') }}
                        @endif
                    </strong>
                </p>
            </div>
        @endif

        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-2 g-4">
            <div class="col" data-aos="fade-right" data-aos-delay="100">
                <div class="card shadow-sm rounded-4 border-0 h-100 hover-shadow transition">
                    <div class="card-header bg-white border-0">
                        <h5 class="mb-0">Pemasukan</h5>
                    </div>
                    <div class="card-body">
                        <canvas id="incomeChart" height="150"></canvas>
                    </div>
                </div>
            </div>
            <div class="col" data-aos="fade-left" data-aos-delay="100">
                <div class="card shadow-sm rounded-4 border-0 h-100 hover-shadow transition">
                    <div class="card-header bg-white border-0">
                        <h5 class="mb-0">Pengeluaran</h5>
                    </div>
                    <div class="card-body">
                        <canvas id="expenseChart" height="150"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-4" >
            <div class="col-12">
                <div class="card shadow-sm rounded-4 border-0 h-100 hover-shadow transition">
                    <div class="card-header bg-white border-0">
                        <h5 class="mb-0">Laba Rugi</h5>
                    </div>
                    <div class="card-body">
                        <canvas id="profitLossChart" height="150"></canvas>
                    </div>
                </div>
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
    new Chart(document.getElementById('incomeChart'), {
        type: 'bar',
        data: {
            labels: @json($financeDates),
            datasets: [{
                label: 'Pemasukan',
                data: @json($financeIncome),
                backgroundColor: 'rgba(54, 162, 235, 0.7)'
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { display: false } },
            scales: { y: { beginAtZero: true } }
        }
    });

    // Pengeluaran - LINE
    new Chart(document.getElementById('expenseChart'), {
        type: 'line',
        data: {
            labels: @json($financeDates),
            datasets: [{
                label: 'Pengeluaran',
                data: @json($financeExpense),
                borderColor: 'rgba(255, 99, 132, 1)',
                backgroundColor: 'rgba(255, 99, 132, 0.2)',
                tension: 0.4,
                fill: true
            }]
        },
        options: {
            responsive: true,
            scales: { y: { beginAtZero: true } }
        }
    });
    
    const profitLossData = @json($financeProfitLoss);
    const profitLossColors = profitLossData.map(value => value >= 0 ? 'rgba(40, 167, 69, 0.3)' : 'rgba(220, 53, 69, 0.3)');
    const profitLossBorderColors = profitLossData.map(value => value >= 0 ? 'rgba(40, 167, 69, 1)' : 'rgba(220, 53, 69, 1)');

    new Chart(document.getElementById('profitLossChart'), {
        type: 'line',
        data: {
            labels: @json($financeDates),
            datasets: [{
                label: 'Laba Rugi',
                data: profitLossData,
                backgroundColor: profitLossColors,
                borderColor: profitLossBorderColors,
                borderWidth: 3, 
                tension: 0.4, 
                pointRadius: 5,
                pointHoverRadius: 7,
                pointBackgroundColor: profitLossBorderColors,
                pointBorderColor: '#fff',
                pointBorderWidth: 2
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: false },
                title: {
                    display: true,
                    text: 'Laba Rugi per Bulan (Hijau = Untung, Merah = Rugi)'
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            const value = context.raw;
                            const label = value >= 0 ? 'Untung: ' : 'Rugi: ';
                            return label + new Intl.NumberFormat('id-ID', {
                                style: 'currency',
                                currency: 'IDR'
                            }).format(value);
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });


});
</script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const filterType = document.getElementById('filter_type');
    const inputMonth = document.getElementById('filter_value_month');
    const inputYear  = document.getElementById('filter_value_year');

    function toggleFilterInput() {
        if (filterType.value === 'year') {
            inputMonth.style.display = 'none';
            inputMonth.disabled      = true;

            inputYear.style.display = '';
            inputYear.disabled      = false;
        } else {
            inputMonth.style.display = '';
            inputMonth.disabled      = false;

            inputYear.style.display = 'none';
            inputYear.disabled      = true;
        }
    }

    toggleFilterInput();
    filterType.addEventListener('change', toggleFilterInput);
});
</script>
@if($showModal)
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const rulesModal = new bootstrap.Modal(document.getElementById('rulesModal'));
        rulesModal.show();
    });
</script>
@endif

@endsection
