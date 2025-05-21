<style>
  .sidebar-wrapper {
    max-height: calc(100vh - 120px); 
    overflow-y: auto;
  }
</style>

<aside class="app-sidebar bg-body-secondary shadow" data-bs-theme="dark">
  <div class="sidebar-brand p-3 border-bottom text-center" style="min-height: 120px;">
    <?php use App\Models\Resources\Company\Company; $company = Company::first(); ?>
    @if($company && $company->image)
      <a href="/dashboard" class="text-decoration-none text-dark d-block" style="display: flex; flex-direction: column; align-items: center;">
        <img
          src="{{ asset($company->image) }}"
          alt="{{ $company->name ?? 'Company Logo' }}"
          class="rounded-circle shadow-sm"
          style="width: 200px; height: 100px; object-fit: cover; margin-bottom: -2px;"
        />
        <div
          class="fw-semibold text-white fs-10 text-wrap text-lg text-truncate mb-2"
          style="max-width: 200px; line-height: 1.2; transform: translateY(-12px);"
          title="{{ $company->name }}"
        >
          {{ $company->name }}
        </div>
      </a>
    @endif
  </div>



  <div class="sidebar-wrapper pt-2 flex-grow-1 overflow-auto">
    <nav>
      <ul class="nav sidebar-menu flex-column" data-lte-toggle="treeview" role="menu" data-accordion="false">
        <li class="nav-item menu-open">
          <a href="/dashboard" class="nav-link active">
            <i class="nav-icon bi bi-speedometer"></i>
            <p>Dashboard</p>
          </a>
        </li>

        <li class="nav-header text-muted text-uppercase small mt-3">Transaksi</li>
        <li class="nav-item">
          <a href="#" class="nav-link">
            <i class="nav-icon bi bi-currency-dollar"></i>
            <p>
              Transaksi
              <i class="nav-arrow bi bi-chevron-right"></i>
            </p>
          </a>
          <ul class="nav nav-treeview ps-4">
            <li class="nav-item">
              <a href="{{ url('transactions/order/') }}" class="nav-link">
                <i class="nav-icon bi bi-circle"></i>
                <p>Pesanan Jasa</p>
              </a>
            </li>
          </ul>
        </li>

        @if(auth()->user()->hasRole(['admin', 'supervisor', 'petugas', 'owner']))
        <li class="nav-header text-muted text-uppercase small mt-3">Setting</li>
        <li class="nav-item">
          <a href="#" class="nav-link">
            <i class="nav-icon bi bi-layers"></i>
            <p>
              Setting
              <i class="nav-arrow bi bi-chevron-right"></i>
            </p>
          </a>
          <ul class="nav nav-treeview ps-4">
            <li class="nav-item">
              <a href="{{ url('setting/rules') }}" class="nav-link">
                <i class="nav-icon bi bi-circle"></i>
                <p>Peraturan Perusahaan</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="{{ url('setting/branch') }}" class="nav-link">
                <i class="nav-icon bi bi-circle"></i>
                <p>Cabang</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="{{ url('setting/category') }}" class="nav-link">
                <i class="nav-icon bi bi-circle"></i>
                <p>Kategori</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="{{ url('setting/brand') }}" class="nav-link">
                <i class="nav-icon bi bi-circle"></i>
                <p>Merk Kendaraan</p>
              </a>
            </li>
             <li class="nav-item">
              <a href="{{ url('setting/vehicle') }}" class="nav-link">
                <i class="nav-icon bi bi-circle"></i>
                <p>Kendaraan</p>
              </a>
            </li>
          </ul>
        </li>
        @endif

        <li class="nav-header text-muted text-uppercase small mt-3">Konfigurasi Aplikasi</li>
        <li class="nav-item">
          <a href="#" class="nav-link">
            <i class="nav-icon bi bi-gear"></i>
            <p>
              Konfigurasi
              <i class="nav-arrow bi bi-chevron-right"></i>
            </p>
          </a>
          <ul class="nav nav-treeview ps-4">
            @if(auth()->user()->hasRole(['admin', 'supervisor']))
            <li class="nav-item">
              <a href="{{ url('configuration/company') }}" class="nav-link">
                <i class="nav-icon bi bi-circle"></i>
                <p>Informasi Perusahaan</p>
              </a>
            </li>
            @endif

            @if(auth()->user()->hasRole(['admin', 'supervisor', 'petugas', 'owner']))
            <li class="nav-item">
              <a href="{{ url('people/users') }}" class="nav-link">
                <i class="nav-icon bi bi-circle"></i>
                <p>Pengguna/User</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="{{ url('people/admin') }}" class="nav-link">
                <i class="nav-icon bi bi-circle"></i>
                <p>Manajemen Admin</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="{{ url('people/supervisor') }}" class="nav-link">
                <i class="nav-icon bi bi-circle"></i>
                <p>Manajemen Supervisor</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="{{ url('people/employee') }}" class="nav-link">
                <i class="nav-icon bi bi-circle"></i>
                <p>Manajemen Petugas</p>
              </a>
            </li>
            @endif
          </ul>
        </li>
      </ul>
    </nav>
  </div>
</aside>
