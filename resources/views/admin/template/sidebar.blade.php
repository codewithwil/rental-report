<aside class="app-sidebar bg-body-secondary shadow" data-bs-theme="dark">
  <div class="sidebar-brand p-3 border-bottom">
    <?php use App\Models\Resources\Company\Company; $company = Company::first(); ?>
    @if($company && $company->image)
      <a href="/dashboard" class="d-flex align-items-center text-decoration-none text-dark">
        <img
          src="{{ asset($company->image) }}"
          alt="{{ $company->name ?? 'Company Logo' }}"
          class="rounded-circle shadow-sm me-2"
          style="width: 50px; height: 50px; object-fit: cover;"
        />
        <span class="fw-semibold fs-5">{{ $company->name }}</span>
      </a>
    @endif
  </div>

  <div class="sidebar-wrapper pt-2">
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
              <a href="{{ url('setting/brand') }}" class="nav-link">
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
