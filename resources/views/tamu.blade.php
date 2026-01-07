<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Reminder Absensi</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap 4 CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">

</head>

<body class="bg-light">

    <div class="container">
        <div class="row justify-content-center align-items-center" style="min-height:100vh;">
            <div class="col-md-8">
                <div class="card shadow text-center">
                    <div class="card-body py-5">

                        <i class="fas fa-bell fa-4x text-success mb-4"></i>

                        <h3 class="font-weight-bold mb-3">
                            Selamat Datang
                        </h3>

                        <p style="font-family: 'Poppins', sans-serif; font-size: 1.05rem; line-height: 1.8;"
                            class="text-muted">
                            <strong>Selamat! {{ Auth::user()->name }}</strong>, Sistem Hadirku SIMONA59 Reminder Absensi
                            telah berhasil terhubung
                            dengan Google
                            Kalender Anda! 🎉
                            Setiap jadwal dan event absensi kini akan otomatis tersinkronisasi ke Google Kalender yang
                            terhubung dengan akun Google terdaftar.
                            Pastikan fitur pengingat dan notifikasi Google Kalender telah diaktifkan, agar informasi
                            Reminder kehadiran dapat diterima secara tepat waktu, akurat, dan mendukung kelancaran
                            pelaksanaan tugas di lingkungan KPPN Majene. ⏰📅
                        </p>
                        <span class="badge badge-success p-2">
                            <i class="fas fa-check-circle"></i> Status: Terhubung
                        </span>
                        <hr>
                        <div class="mt-4">
                            @if (Auth::user()->role == 'superadmin')
                                <!-- Alert Dashboard -->
                                <div class="alert alert-info text-center" role="alert">
                                    <i class="fas fa-info-circle"></i>
                                    Klik tombol <strong>Dashboard</strong> untuk melanjutkan ke halaman utama sistem
                                    SIMONA59.
                                </div>
                            @endif


                            <!-- Alert Logout -->
                            <div class="alert alert-warning text-center" role="alert">
                                <i class="fas fa-exclamation-triangle"></i>
                                Klik <strong>Logout</strong> untuk keluar dari sistem. Silahkan Menghubungi admin, Jika
                                mendapat kendala dan Reminder tidak masuk ke Google Calendar.
                            </div>

                            @if (Auth::user()->role == 'superadmin')
                                <!-- Tombol Aksi -->
                                <a href="{{ route('home') }}" class="btn btn-primary mr-2">
                                    <i class="fas fa-tachometer-alt"></i> Dashboard
                                </a>
                            @endif

                            <a href="#" class="btn btn-danger"
                                onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                <i class="fas fa-sign-out-alt"></i> Logout
                            </a>

                            <!-- Form Logout (Laravel) -->
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                @csrf
                            </form>

                        </div>



                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap 4 JS -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>
