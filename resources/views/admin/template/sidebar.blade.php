<style>
  .sidebar-wrapper {
    max-height: calc(100vh - 120px); 
    overflow-y: auto;
  }

  .sidebar-menu .nav-link.active {
    background-color: #0d6efd !important;
    color: #fff !important;
  }

  .sidebar-menu .nav-link:hover {
    background-color: rgba(13, 110, 253, 0.1);
  }

  .sidebar-menu .nav-treeview .nav-link {
    padding-left: 2.5rem;
    border-left: 2px solid #dee2e6;
  }

  .sidebar-brand img {
    transition: transform 0.3s ease;
  }

  .sidebar-brand img:hover {
    transform: scale(1.05);
  }

  .nav-header {
    font-size: 0.75rem;
    font-weight: 600;
    padding-left: 1rem;
    padding-top: 0.75rem;
    color: #adb5bd;
  }

.sidebar-menu .nav-link p {
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
  margin-bottom: 0;
}

</style>

<aside class="app-sidebar bg-body-secondary shadow" data-bs-theme="dark">
  <div class="sidebar-brand p-3 border-bottom text-center" style="min-height: 120px;">
    <?php use App\Models\Resources\Company\Company; $company = Company::first(); ?>
    @if($company && $company->image)
      <a href="/dashboard" class="text-decoration-none d-flex flex-column align-items-center">
        <img
          src="{{ asset($company->image) }}"
          alt="{{ $company->name ?? 'Company Logo' }}"
          class="rounded-circle shadow-sm"
          style="width: 80px; height: 80px; object-fit: cover;"
        />
        <div class="fw-semibold text-white text-center mt-2" style="font-size: 0.9rem;" title="{{ $company->name }}">
          {{ Str::limit($company->name, 24) }}
        </div>
      </a>
    @endif
  </div>

  <div class="sidebar-wrapper pt-2">
    <nav>
      <ul class="nav sidebar-menu flex-column" data-lte-toggle="treeview" role="menu" data-accordion="false">

        <li class="nav-item">
          <a href="/dashboard" class="nav-link {{ request()->is('dashboard') ? 'active' : '' }}">
            <i class="nav-icon bi bi-speedometer"></i>
            <p>Dashboard</p>
          </a>
        </li>

        {{-- Laporan --}}
        @if(auth()->user()->hasRole(['admin', 'supervisor', 'petugas']))
          <li class="nav-header">Laporan</li>
          <li class="nav-item {{ request()->is('report/*') ? 'menu-open' : '' }}">
            <a href="#" class="nav-link">
              <i class="nav-icon bi bi-envelope"></i>
              <p>
                Laporan
                <i class="nav-arrow bi bi-chevron-right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item"><a href="{{ url('report/weeklyReport') }}" class="nav-link {{ request()->is('report/weeklyReport') ? 'active' : '' }}"><i class="nav-icon bi bi-circle"></i> <p>Laporan Mingguan</p></a></li>
              <li class="nav-item"><a href="{{ url('report/vehicleRepair') }}" class="nav-link {{ request()->is('report/vehicleRepair') ? 'active' : '' }}"><i class="nav-icon bi bi-circle"></i> <p>Ajukan Perbaikan</p></a></li>
              <li class="nav-item"><a href="{{ url('report/kas') }}" class="nav-link {{ request()->is('report/kas') ? 'active' : '' }}"><i class="nav-icon bi bi-circle"></i> <p>Kas Keluar/Masuk</p></a></li>
            </ul>
          </li>
        @endif

        {{-- Transaksi --}}
        @if(auth()->user()->hasRole(['admin', 'supervisor']))
          <li class="nav-header">Transaksi</li>
          <li class="nav-item {{ request()->is('transactions/*') ? 'menu-open' : '' }}">
            <a href="#" class="nav-link">
              <i class="nav-icon bi bi-currency-dollar"></i>
              <p>
                Transaksi
                <i class="nav-arrow bi bi-chevron-right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item"><a href="{{ url('transactions/vehicleRepairReal') }}" class="nav-link  {{ request()->is('transactions/vehicleRepairReal') ? 'active' : '' }}"><i class="nav-icon bi bi-circle"></i> <p>Nota Perbaikan</p></a></li>
              <li class="nav-item"><a href="{{ url('transactions/rentCar') }}" class="nav-link  {{ request()->is('transactions/rentCar') ? 'active' : '' }}"><i class="nav-icon bi bi-circle"></i> <p>Sewa Mobil</p></a></li>
              <li class="nav-item"><a href="{{ url('transactions/returnRentCar') }}" class="nav-link  {{ request()->is('transactions/returnRentCar') ? 'active' : '' }}"><i class="nav-icon bi bi-circle"></i> 
                <p data-bs-toggle="tooltip" data-bs-placement="right" title="Pengembalian Sewa Mobil">
                  Pengembalian Sewa Mobil
                </p>

              </a></li>
            </ul>
          </li>
        @endif

        {{-- Riwayat --}}
        @if(auth()->user()->hasRole(['admin']))
          <li class="nav-header">Riwayat</li>
          <li class="nav-item {{ request()->is('history/*') ? 'menu-open' : '' }}">
            <a href="#" class="nav-link">
              <i class="nav-icon bi bi-clock-history"></i>
              <p>
                Riwayat
                <i class="nav-arrow bi bi-chevron-right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item"><a href="{{ url('history/notification') }}" class="nav-link {{ request()->is('history/notification') ? 'active' : '' }}"><i class="nav-icon bi bi-circle"></i> <p>Notifikasi</p></a></li>
              <li class="nav-item"><a href="{{ url('history/activities') }}" class="nav-link {{ request()->is('history/activities') ? 'active' : '' }}"><i class="nav-icon bi bi-circle"></i> <p>Log Aktivitas</p></a></li>
            </ul>
          </li>
        @endif

        {{-- Manajemen --}}
        @if(auth()->user()->hasRole(['admin', 'supervisor']))
          <li class="nav-header">Manajemen Data</li>
          <li class="nav-item {{ request()->is('setting/*') ? 'menu-open' : '' }}">
            <a href="#" class="nav-link">
              <i class="nav-icon bi bi-layers"></i>
              <p>
                Manajemen
                <i class="nav-arrow bi bi-chevron-right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item"><a href="{{ url('setting/rules') }}" class="nav-link {{ request()->is('setting/rules') ? 'active' : '' }}"><i class="nav-icon bi bi-circle"></i> <p>Peraturan</p></a></li>
              <li class="nav-item"><a href="{{ url('setting/branch') }}" class="nav-link {{ request()->is('setting/branch') ? 'active' : '' }}"><i class="nav-icon bi bi-circle"></i> <p>Cabang</p></a></li>
              <li class="nav-item"><a href="{{ url('setting/category') }}" class="nav-link {{ request()->is('setting/category') ? 'active' : '' }}"><i class="nav-icon bi bi-circle"></i> <p>Kategori</p></a></li>
              <li class="nav-item"><a href="{{ url('setting/brand') }}" class="nav-link {{ request()->is('setting/brand') ? 'active' : '' }}"><i class="nav-icon bi bi-circle"></i> <p>Merk Kendaraan</p></a></li>
              <li class="nav-item"><a href="{{ url('setting/vehicle') }}" class="nav-link {{ request()->is('setting/vehicle') ? 'active' : '' }}"><i class="nav-icon bi bi-circle"></i> <p>Kendaraan</p></a></li>
            </ul>
          </li>
        @endif

        {{-- Konfigurasi --}}
        @if(auth()->user()->hasRole(['admin', 'supervisor']))
          <li class="nav-header">Konfigurasi Aplikasi</li>
          <li class="nav-item {{ request()->is('configuration/*') ? 'menu-open' : '' }}">
            <a href="#" class="nav-link">
              <i class="nav-icon bi bi-gear"></i>
              <p>
                Konfigurasi
                <i class="nav-arrow bi bi-chevron-right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              @if(auth()->user()->hasRole('admin'))
                <li class="nav-item"><a href="{{ url('configuration/company') }}" class="nav-link {{ request()->is('configuration/company') ? 'active' : '' }}"><i class="nav-icon bi bi-circle"></i> <p>Informasi Perusahaan</p></a></li>
              @endif
              <li class="nav-item"><a href="{{ url('people/users') }}" class="nav-link {{ request()->is('configuration/users') ? 'active' : '' }}"><i class="nav-icon bi bi-circle"></i> <p>Pengguna</p></a></li>
              @if(auth()->user()->hasRole('admin'))
                <li class="nav-item"><a href="{{ url('people/admin') }}" class="nav-link {{ request()->is('configuration/admin') ? 'active' : '' }}"><i class="nav-icon bi bi-circle"></i> <p>Admin</p></a></li>
                <li class="nav-item"><a href="{{ url('people/supervisor') }}" class="nav-link {{ request()->is('configuration/supervisor') ? 'active' : '' }}"><i class="nav-icon bi bi-circle"></i> <p>Supervisor</p></a></li>
              @endif
              <li class="nav-item"><a href="{{ url('people/employee') }}" class="nav-link {{ request()->is('configuration/employee') ? 'active' : '' }}"><i class="nav-icon bi bi-circle"></i> <p>Petugas</p></a></li>
            </ul>
          </li>
        @endif

      </ul>
    </nav>
  </div>
</aside>

<script>
  document.addEventListener('DOMContentLoaded', function () {
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    tooltipTriggerList.forEach(function (tooltipTriggerEl) {
      new bootstrap.Tooltip(tooltipTriggerEl)
    })
  });
</script>
