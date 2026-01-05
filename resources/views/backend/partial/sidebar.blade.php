    <ul class="navbar-nav sidebar sidebar-light accordion" id="accordionSidebar">
        <a class="sidebar-brand d-flex align-items-center justify-content-center" href="index.html">
            <div class="sidebar-brand-icon">
                <img src="img/logo/logo2.png">
            </div>
            <div class="sidebar-brand-text mx-3">KPPN</div>
        </a>
        <hr class="sidebar-divider my-0">
        <li class="nav-item">
            <a class="nav-link" href="{{ route('home') }}">
                <i class="fas fa-fw fa-tachometer-alt"></i>
                <span>Dashboard</span></a>
        </li>
        <hr class="sidebar-divider">
        <div class="sidebar-heading">
            Features
        </div>
        <li class="nav-item">
            <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#data" aria-expanded="true"
                aria-controls="collapseTable">
                <i class="fas fa-database"></i>
                <span>Data</span>
            </a>
            <div id="data" class="collapse" aria-labelledby="headingTable" data-parent="#accordionSidebar">
                <div class="bg-white py-2 collapse-inner rounded">
                    <h6 class="collapse-header">Daftar Data</h6>
                    <a class="collapse-item" href="{{ route('employees.index') }}">Data Pegawai</a>
                    <a class="collapse-item" href="{{ route('agencies.index') }}">Data Satker</a>
                    <a class="collapse-item" href="{{ route('users.index') }}">Manajemen User</a>
                </div>
            </div>
        </li>
        <li class="nav-item">
            <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#absen"
                aria-expanded="true" aria-controls="collapseTable">
                <i class="fas fa-user-clock"></i>
                <span>Absensi PPNPN</span>
            </a>
            <div id="absen" class="collapse" aria-labelledby="headingTable" data-parent="#accordionSidebar">
                <div class="bg-white py-2 collapse-inner rounded">
                    <h6 class="collapse-header">Absensi</h6>
                    <a class="collapse-item" href="{{ route('attendance.index') }}">Halaman Absensi</a>
                    <a class="collapse-item" href="{{ route('attendances.data') }}">Data Absensi</a>
                </div>
            </div>
        </li>
        <li class="nav-item">
            <a class="nav-link collapsed" href="{{ route('guest_book_index') }}" aria-expanded="true"
                aria-controls="collapseTable">
                <i class="fas fa-address-book"></i>
                <span>Buku Tamu</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseemail"
                aria-expanded="true" aria-controls="collapseTable">
                <i class="fas fa-bell"></i>
                <span>Reminder</span>
            </a>
            <div id="collapseemail" class="collapse" aria-labelledby="headingTable" data-parent="#accordionSidebar">
                <div class="bg-white py-2 collapse-inner rounded">
                    <h6 class="collapse-header">Reminder</h6>
                    <a class="collapse-item" href="{{ route('work-schedules.index') }}">Jadwal Reminder</a>
                    <a class="collapse-item" href="{{ route('google-accounts.index') }}">Akun google terdaftar</a>
                    <a class="collapse-item" href="{{ route('reminder-logs.index') }}">Monitoring Reminder</a>
                </div>
            </div>
        </li>
        <li class="nav-item">
            <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseTable"
                aria-expanded="true" aria-controls="collapseTable">
                <i class="fas fa-chart-line"></i>
                <span>WBK-WBBM</span>
            </a>
            <div id="collapseTable" class="collapse" aria-labelledby="headingTable" data-parent="#accordionSidebar">
                <div class="bg-white py-2 collapse-inner rounded">
                    <h6 class="collapse-header">WBK-WBBM</h6>
                    <a class="collapse-item" href="{{ route('wbbm-tes-progres') }}">Cek Progress</a>
                    <a class="collapse-item" href="{{ route('wbbm-data') }}">Indikator Capaian</a>
                </div>
            </div>
        </li>
        <hr class="sidebar-divider">
        <div class="version" id="version-ruangadmin"></div>
    </ul>
