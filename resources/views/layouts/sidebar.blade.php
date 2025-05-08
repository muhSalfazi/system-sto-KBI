<aside id="sidebar" class="sidebar hiden">
    <ul class="sidebar-nav" id="sidebar-nav">
        <li class="nav-heading">Inventory List</li>

        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('sto.index','sto.create.get') ? 'active' : 'collapsed' }}"
                href="{{ route('sto.index') }}">
                <i class="bi bi-box-seam-fill"></i>
                <span>List STO</span>
            </a>
        </li>
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
                <span>Detail Lokasi</span>
            </a>
        </li>


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

<style>
    .sidebar-nav {
        padding: 0;
        margin: 0;
        list-style: none;
        /* Remove default list styles */
    }

    .nav-item {
        position: relative;
        /* Positioning for hover effect */
    }

    .nav-link {
        display: flex;
        /* Flexbox for horizontal alignment */
        align-items: center;
        /* Center align items */
        padding: 15px 20px;
        /* Padding for links */
        color: #aab8c9;
        /* Default text color */
        text-decoration: none;
        /* Remove underline */
        transition: background-color 0.3s, color 0.3s;
        /* Smooth transition */
    }

    .nav-heading {
        color: #ccf1fa;
        /* Heading color */
        padding: 15px 20px;
        /* Padding for headings */
        font-weight: bold;
        /* Bold font for headings */
    }

    .nav-link.active {
        background-color: #8393a4;
        /* Active link background */
        color: #ecf0f1;
        /* Active link text color */
    }

    .nav-link.active i {
        color: #ecf0f1;
        /* Active icon color */
    }

    .nav-link:hover {
        background-color: #adcbe9;
        /* Hover background color */
        color: #ecf0f1;
        /* Hover text color */
    }

    .nav-link i {
        margin-right: 10px;
        /* Spacing between icon and text */
        transition: color 0.3s;
        /* Smooth transition for icons */
    }

    .nav-link:hover i {
        color: #ecf0f1;
        /* Icon color on hover */
    }

    /* Optional: Add a slight shadow to the sidebar for depth */
    .sidebar {
        box-shadow: 2px 0 5px rgba(0, 0, 0, 0.2);
    }
</style>
