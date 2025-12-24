    <ul class="navbar-nav sidebar sidebar-light accordion" id="accordionSidebar">
        <a class="sidebar-brand d-flex align-items-center justify-content-center" href="index.html">
            <div class="sidebar-brand-icon">
                <img src="img/logo/logo2.png">
            </div>
            <div class="sidebar-brand-text mx-3">KPPN</div>
        </a>
        <hr class="sidebar-divider my-0">
        <li class="nav-item">
            <a class="nav-link" href="index.html">
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
                <i class="fab fa-fw fa-wpforms"></i>
                <span>Data</span>
            </a>
            <div id="data" class="collapse" aria-labelledby="headingTable" data-parent="#accordionSidebar">
                <div class="bg-white py-2 collapse-inner rounded">
                    <h6 class="collapse-header">Daftar Data</h6>
                    <a class="collapse-item" href="{{ route('employees.index') }}">Data Pegawai</a>
                    <a class="collapse-item" href="#">Akun google Terdaftar</a>
                </div>
            </div>
        </li>
        <li class="nav-item">
            <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseGuest"
                aria-expanded="true" aria-controls="collapseTable">
                <i class="fab fa-fw fa-wpforms"></i>
                <span>Buku Tamu</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseemail"
                aria-expanded="true" aria-controls="collapseTable">
                <i class="fab fa-fw fa-wpforms"></i>
                <span>Reminder</span>
            </a>
            <div id="collapseemail" class="collapse" aria-labelledby="headingTable" data-parent="#accordionSidebar">
                <div class="bg-white py-2 collapse-inner rounded">
                    <h6 class="collapse-header">Buku Tamu</h6>
                    <a class="collapse-item" href="#">Penerima Email</a>
                    <a class="collapse-item" href="#">Template Email</a>
                </div>
            </div>
        </li>
        <li class="nav-item">
            <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseTable"
                aria-expanded="true" aria-controls="collapseTable">
                <i class="fas fa-fw fa-table"></i>
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
