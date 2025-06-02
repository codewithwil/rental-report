@extends('admin.template.template')
@section('title', 'Dashboard')

@section('content')
<div class="app-content-header">
    <div class="container-fluid">
      <div class="row">
        <div class="col-sm-6"><h3 class="mb-0">Dashboard</h3></div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-end">
            <li class="breadcrumb-item"><a href="#">Home</a></li>
            <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
          </ol>
        </div>
      </div>
    </div>
  </div>
  <div class="app-content">
    <div class="container-fluid">
      <div class="row">
        <div class="col-lg-4 col-6">
          <div class="small-box text-bg-success">
            <div class="inner">
              <h3>{{ $branch }}<sup class="fs-5"></sup></h3>
              <p>Cabang</p>
            </div>
            <svg
              class="small-box-icon"
              fill="currentColor"
              viewBox="0 0 24 24"
              xmlns="http://www.w3.org/2000/svg"
              aria-hidden="true"
            >
              <path
                d="M18.375 2.25c-1.035 0-1.875.84-1.875 1.875v15.75c0 1.035.84 1.875 1.875 1.875h.75c1.035 0 1.875-.84 1.875-1.875V4.125c0-1.036-.84-1.875-1.875-1.875h-.75zM9.75 8.625c0-1.036.84-1.875 1.875-1.875h.75c1.036 0 1.875.84 1.875 1.875v11.25c0 1.035-.84 1.875-1.875 1.875h-.75a1.875 1.875 0 01-1.875-1.875V8.625zM3 13.125c0-1.036.84-1.875 1.875-1.875h.75c1.036 0 1.875.84 1.875 1.875v6.75c0 1.035-.84 1.875-1.875 1.875h-.75A1.875 1.875 0 013 19.875v-6.75z"
              ></path>
            </svg>
            <a
              href="{{url('/setting/branch')}}"
              class="small-box-footer link-light link-underline-opacity-0 link-underline-opacity-50-hover"
            >
              More info <i class="bi bi-link-45deg"></i>
            </a>
          </div>
          <!--end::Small Box Widget 2-->
        </div>

        <div class="col-lg-4 col-6">
          <div class="small-box text-bg-primary">
            <div class="inner">
              <h3>{{ $vehicle }}<sup class="fs-5"></sup></h3>
              <p>Kendaraan</p>
            </div>
            <svg
              class="small-box-icon"
              fill="currentColor"
              viewBox="0 0 24 24"
              xmlns="http://www.w3.org/2000/svg"
              aria-hidden="true"
            >
              <path
                d="M4.5 13.5a1.5 1.5 0 113 0 1.5 1.5 0 01-3 0zm12 0a1.5 1.5 0 113 0 1.5 1.5 0 01-3 0zM3 7.5A3 3 0 016 4.5h12a3 3 0 013 3v6a1.5 1.5 0 01-1.5 1.5h-.75v1.5a1.5 1.5 0 01-3 0V15H7.5v1.5a1.5 1.5 0 01-3 0V15H3.75A1.5 1.5 0 012.25 13.5v-6z"
              ></path>
            </svg>
            <a
              href="{{url('/setting/vehicle')}}"
              class="small-box-footer link-light link-underline-opacity-0 link-underline-opacity-50-hover"
            >
              More info <i class="bi bi-link-45deg"></i>
            </a>
          </div>
        </div>

        <!--end::Col-->
        <div class="col-lg-4 col-6">
          <!--begin::Small Box Widget 3-->
          <div class="small-box text-bg-warning">
            <div class="inner">
              <h3>{{ $users }}</h3>
              <p>Users</p>
            </div>
            <svg
              class="small-box-icon"
              fill="currentColor"
              viewBox="0 0 24 24"
              xmlns="http://www.w3.org/2000/svg"
              aria-hidden="true"
            >
              <path
                d="M6.25 6.375a4.125 4.125 0 118.25 0 4.125 4.125 0 01-8.25 0zM3.25 19.125a7.125 7.125 0 0114.25 0v.003l-.001.119a.75.75 0 01-.363.63 13.067 13.067 0 01-6.761 1.873c-2.472 0-4.786-.684-6.76-1.873a.75.75 0 01-.364-.63l-.001-.122zM19.75 7.5a.75.75 0 00-1.5 0v2.25H16a.75.75 0 000 1.5h2.25v2.25a.75.75 0 001.5 0v-2.25H22a.75.75 0 000-1.5h-2.25V7.5z"
              ></path>
            </svg>
            <a
              href="/people/users"
              class="small-box-footer link-dark link-underline-opacity-0 link-underline-opacity-50-hover"
            >
              More info <i class="bi bi-link-45deg"></i>
            </a>
          </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5>Grafik Laporan Mingguan</h5>
                    </div>
                    <div class="card-body">
                        <canvas id="statusChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

      </div>
    </div>
  </div>

  <!-- Modal Rules Perusahaan -->
  <div class="modal fade" id="rulesModal" tabindex="-1" aria-labelledby="rulesModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="rulesModalLabel">Aturan Perusahaan</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <p>Selamat datang di dashboard. Berikut beberapa aturan perusahaan yang wajib dipatuhi:</p>
            @php
              $lines = explode("\n", $rules->content);
            @endphp
            @foreach ($lines as $line)
              <p>{{ $line }}</p>
            @endforeach
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Saya Mengerti</button>
          </div>
        </div>
    </div>
  </div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    var ctx = document.getElementById('statusChart').getContext('2d');

    var chart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: @json($chartDates),
            datasets: [
                {
                    label: 'Laporan perlu divalidasi',
                    data: @json(collect($chartData)->map(fn($d) => $d['pending'])->values()),
                    borderColor: 'rgba(255, 206, 86, 1)',
                    backgroundColor: 'rgba(255, 206, 86, 0.2)',
                    tension: 0.3,
                    fill: true
                },
                {
                    label: 'Laporan disetujui',
                    data: @json(collect($chartData)->map(fn($d) => $d['approve'])->values()),
                    borderColor: 'rgba(75, 192, 192, 1)',
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    tension: 0.3,
                    fill: true
                },
                {
                    label: 'Laporan ditolak',
                    data: @json(collect($chartData)->map(fn($d) => $d['rejected'])->values()),
                    borderColor: 'rgba(255, 99, 132, 1)',
                    backgroundColor: 'rgba(255, 99, 132, 0.2)',
                    tension: 0.3,
                    fill: true
                },
            ]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                    stepSize: 1
                }
            }
        }
    });

    var rulesModal = new bootstrap.Modal(document.getElementById('rulesModal'));
    rulesModal.show();
});
</script>

  
@endsection