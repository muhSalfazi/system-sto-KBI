<aside id="sidebar" class="sidebar hiden">
    <ul class="sidebar-nav" id="sidebar-nav">
         <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : 'collapsed' }}"
                    href="{{ route('dashboard') }}">
                 <i class="bi bi-grid-1x2-fill"></i>
                    <span>Dashboard</span>
                </a>
        </li>
         <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('forecast.index','forecast.create') ? 'active' : 'collapsed' }}"
                    href="{{ route('forecast.index') }}">
                 <i class="bi bi-graph-up"></i>
                    <span>Forecast</span>
                </a>
        </li>
        @if (in_array(Auth::user()->role->name, ['SuperAdmin', 'admin', 'view']))
            <li class="nav-heading">Inventory List</li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('sto.index', 'sto.create.get', 'sto.edit') ? 'active' : 'collapsed' }}"
                    href="{{ route('sto.index') }}">
                    <i class="bi bi-box-seam-fill"></i>
                    <span>List STO</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('daily-stock.index','reports.edit') ? 'active' : 'collapsed' }}"
                    href="{{ route('daily-stock.index') }}">
                   <i class="bi bi-clipboard-fill"></i>
                    <span>Daily Stok</span>
                </a>
            </li>
        @endif
        @if (in_array(Auth::user()->role->name, ['SuperAdmin', 'admin']))
            <li class="nav-heading">Master Data</li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('parts.index', 'parts.create', 'parts.edit') ? 'active' : 'collapsed' }}"
                    href="{{ route('parts.index') }}">
                    <i class="bi bi-archive"></i>
                    <span>Part</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('detail-lokasi.index', 'create.detail-lokasi', 'edit.detail-lokasi') ? 'active' : 'collapsed' }}"
                    href="{{ route('detail-lokasi.index') }}">
                    <i class="bi bi-pin-map"></i>
                    <span>Location Details</span>
                </a>
            </li>
        @endif

        @if (Auth::user()->role->name == 'SuperAdmin')
            <li class="nav-heading">User Management</li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('users.index', 'users.create', 'users.edit') ? 'active' : 'collapsed' }}"
                    href="{{ route('users.index') }}">
                    <i class="bi bi-people-fill"></i>
                    <span>User</span>
                </a>
            </li>
        @endif

        <li class="nav-heading">Auth</li>
        <li class="nav-item">
            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                @csrf
            </form>
            <a class="nav-link collapsed" href="#" onclick="logoutConfirm()">
                <i class="bi bi-box-arrow-left"></i>
                <span>Logout</span>
            </a>
        </li>
    </ul>
</aside>
<!-- End Sidebar-->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function logoutConfirm() {
        Swal.fire({
            title: 'Anda yakin ingin logout?',
            text: "Anda akan keluar dari sesi ini.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, logout!',
            cancelButtonText: 'Batal',
            showClass: {
                popup: 'animate__animated animate__jackInTheBox' // Animasi saat muncul
            },
            hideClass: {
                popup: 'animate__animated animate__fadeOutUp' // Animasi saat menghilang
            }
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('logout-form').submit();
            }
        });
    }
</script>
