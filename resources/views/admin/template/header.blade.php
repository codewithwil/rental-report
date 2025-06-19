<nav class="app-header navbar navbar-expand bg-body">
    <div class="container-fluid">
      <ul class="navbar-nav">
        <li class="nav-item">
          <a class="nav-link" data-lte-toggle="sidebar" href="#" role="button">
            <i class="bi bi-list"></i>
          </a>
        </li>
        <li class="nav-item d-none d-md-block"><a href="#" class="nav-link">Home</a></li>
        <li class="nav-item d-none d-md-block"><a href="#" class="nav-link">Contact</a></li>
        <li class="nav-item d-none d-md-block">
          <span class="nav-link">
              <strong>Cabang {{ \Illuminate\Support\Str::replace('@branch.com', '', Auth::user()->branch->email ?? 'Utama')}}</strong>
          </span>
        </li>
        
      </ul>
      <ul class="navbar-nav ms-auto">
        <li class="nav-item dropdown">
            <a class="nav-link" data-bs-toggle="dropdown" href="#">
                <i class="bi bi-bell-fill"></i>
                <span class="navbar-badge rounded-1 text-bg-warning">
                    {{ $notificationsGrouped->flatten()->count() }}
                </span>
            </a>
            <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end">
                <span class="dropdown-item dropdown-header">
                    {{ $notificationsGrouped->flatten()->count() }} Notifications
                </span>
                <div class="dropdown-divider"></div>

                @foreach($notificationsGrouped as $group => $notifs)
                    <a href="#" class="dropdown-item" data-bs-toggle="modal" data-bs-target="#modal-{{ Str::slug($group) }}">
                        <i class="bi bi-folder me-2"></i> {{ $group }} ({{ $notifs->count() }})
                    </a>
                    <div class="dropdown-divider"></div>
                @endforeach

                <a href="/notifications" class="dropdown-item dropdown-footer">See All Notifications</a>
            </div>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#" data-lte-toggle="fullscreen">
            <i data-lte-icon="maximize" class="bi bi-arrows-fullscreen"></i>
            <i data-lte-icon="minimize" class="bi bi-fullscreen-exit" style="display: none"></i>
          </a>
        </li>
        <li class="nav-item dropdown user-menu">
          <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">
            <img
              src="{{ asset('images/user.png') }}"
              class="user-image rounded-circle shadow"
              alt="User Image"
            />
            <span class="d-none d-md-inline">
              Hallo, {{ optional(Auth::user()->admin)->name }}
               {{ optional(Auth::user()->supervisor)->name }}
              {{ optional(Auth::user()->employee)->name }}
              {{ Auth::user()->getRoleNames()->first() }}
          </span>
          
          </a>
          <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-end">
            <!--begin::User Image-->
            <li class="user-header text-bg-primary">
              <img
                src="{{ asset('images/user.png') }}"
                class="rounded-circle shadow"
                alt="User Image"
              />
              <p>
                {{ Auth::user()->realName() }} | {{ Auth::user()->getRoleNames()->first() }}

                <small>Member since Nov. 2023</small>
              </p>
            </li>
            <!--end::User Image-->
            <!--begin::Menu Body-->
            <li class="user-body">
              <!--begin::Row-->
              <div class="row">
                <div class="col-4 text-center"><a href="#">Followers</a></div>
                <div class="col-4 text-center"><a href="#">Sales</a></div>
                <div class="col-4 text-center"><a href="#">Friends</a></div>
              </div>
              <!--end::Row-->
            </li>
            <!--end::Menu Body-->
            <!--begin::Menu Footer-->
            <li class="user-footer">
              <a href="#" class="btn btn-default btn-flat">Profile</a>
              <a href="/logout" class="btn btn-default btn-flat float-end">Sign out</a>
            </li>
            <!--end::Menu Footer-->
          </ul>
        </li>
        <!--end::User Menu Dropdown-->
      </ul>
      <!--end::End Navbar Links-->
    </div>
    <!--end::Container-->
  </nav>

  @foreach($notificationsGrouped as $group => $notifs)
<div class="modal fade" id="modal-{{ Str::slug($group) }}" tabindex="-1" aria-labelledby="modalLabel-{{ Str::slug($group) }}" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
          <div class="modal-header">
              <h5 class="modal-title">{{ $group }} Notifications ({{ $notifs->count() }})</h5>
              <button type="button" class="btn btn-sm btn-outline-secondary ms-2" onclick="toggleSelectMode('{{ Str::slug($group) }}')">Pilih</button>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>

            <div class="modal-body">
                <form action="{{ url('/notification/bulkDestroy') }}" method="POST" id="form-{{ Str::slug($group) }}">
                    @csrf
                    <input type="hidden" name="group" value="{{ $group }}">
                    @forelse($notifs as $notif)
                    <div class="d-flex justify-content-between align-items-center mb-2 p-2 border rounded notif-item" data-group="{{ Str::slug($group) }}">
                      <div class="content-clickable" style="cursor:pointer;">
                        <input type="checkbox" name="notifications[]" value="{{ $notif->id }}" class="form-check-input me-2 d-none select-mode-{{ Str::slug($group) }}">
                        <i class="bi bi-info-circle me-2"></i> 
                        <strong>{{ $notif->title }}</strong><br>
                        <small class="text-muted">{{ $notif->created_at->diffForHumans() }}</small>
                      </div>
                      <div>
                        <a href="{{ $notif->link }}" class="btn btn-sm btn-primary">View</a>
                      </div>
                    </div>

                    @empty
                        <p class="text-center text-muted">Tidak Ada Notifikasi.</p>
                    @endforelse
                </form>
            </div>

          <div class="modal-footer">
              <button type="submit" form="form-{{ Str::slug($group) }}" class="btn btn-danger"
                  onclick="return confirm('Are you sure you want to delete selected notifications?');">
                  Hapus
              </button>
              <form action="{{ url('notification/deleteGroup') }}" method="POST" class="d-inline" onsubmit="return confirm('Delete all notifications in {{ $group }}?');">
                  @csrf
                  <input type="hidden" name="group" value="{{ $group }}">
                  <button type="submit" class="btn btn-warning">Hapus Semua Notifikasi {{ $group }}</button>
              </form>
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          </div>
        </div>
    </div>
</div>

@push('js')
<script>
    function toggleSelectMode(group) {
        const checkboxes = document.querySelectorAll(`.select-mode-${group}`);
        checkboxes.forEach(cb => cb.classList.toggle('d-none'));
    }

    document.querySelectorAll('.notif-item').forEach(item => {
        item.addEventListener('contextmenu', e => {
            e.preventDefault();
            const group = item.dataset.group;
            toggleSelectMode(group);
        });

        item.addEventListener('touchstart', function(e) {
            this.touchStartTime = Date.now();
        });
        item.addEventListener('touchend', function(e) {
            if (Date.now() - this.touchStartTime > 500) { 
                const group = this.dataset.group;
                toggleSelectMode(group);
            }
        });
    });

    document.querySelectorAll('.notif-item .content-clickable').forEach(el => {
        el.addEventListener('click', (e) => {
            const checkbox = el.querySelector('input[type="checkbox"]');
            if (checkbox) {
                checkbox.checked = !checkbox.checked;
                checkbox.dispatchEvent(new Event('change'));
            }
        });
    });
</script>
@endpush

@endforeach
