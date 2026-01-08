@extends('master')

@section('content')
    <div class="container-fluid">
        <div class="card shadow-sm mb-2">
            <div class="card-header py-3">
                <h5 class="m-0 font-weight-bold text-primary">📸 Absensi Pegawai</h5>
            </div>
            <div class="card-body">
                <div class="alert alert-warning text-left" role="alert">
                    <i class="fas fa-exclamation-triangle"></i>
                    Pastikan Untuk Aktifkan <strong>lokasi/GPS</strong> dan <strong>Kamera</strong> terlebih dahulu, sebelum
                    melakukan
                    Konfirmasi Absensi.
                </div>
                <div id="clock" class="mb-2 text-primary fw-bold"></div>

                <select id="shift" class="form-control mb-2">
                    <option value="">-- Pilih Shift --</option>
                    @foreach ($shifts as $shift)
                        <option value="{{ $shift->id }}">
                            {{ $shift->shift_name }} ({{ $shift->start_time }} - {{ $shift->end_time }})
                        </option>
                    @endforeach
                </select>

                <select id="type" class="form-control mb-2">
                    <option value="DATANG">Datang</option>
                    <option value="PULANG">Pulang</option>
                </select>

                <video id="video" width="100%" autoplay class="border mb-2"></video>
                <canvas id="canvas" class="d-none"></canvas>

                <button class="btn btn-primary w-100 mb-2" onclick="openCamera()">
                    Buka Kamera
                </button>

                <button class="btn btn-success w-100" onclick="submitAttendance()">
                    Konfirmasi Absensi
                </button>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script>
        function showAlert(icon, title, text) {
            Swal.fire({
                icon: icon,
                title: title,
                text: text,
                confirmButtonColor: '#4e73df'
            });
        }

        let latitude = null;
        let longitude = null;
        let stream = null;

        // 🕒 Jam realtime
        setInterval(() => {
            document.getElementById('clock').innerText =
                new Date().toLocaleString('id-ID');
        }, 1000);

        // 📍 Ambil GPS
        navigator.geolocation.getCurrentPosition(pos => {
            latitude = pos.coords.latitude;
            longitude = pos.coords.longitude;
        }, err => {
            showAlert(
                'warning',
                'GPS Tidak Aktif',
                'Silakan aktifkan GPS untuk melanjutkan absensi'
            );

        });

        // 📷 Kamera (realtime only)
        function openCamera() {

            if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
                showAlert(
                    'error',
                    'Tidak Didukung',
                    'Browser Anda tidak mendukung akses kamera'
                );

                return;
            }

            navigator.mediaDevices.getUserMedia({
                    video: {
                        facingMode: 'user'
                    },
                    audio: false
                })
                .then(s => {
                    stream = s;
                    const video = document.getElementById('video');
                    video.srcObject = s;
                    video.play();
                })
                .catch(err => {
                    console.error(err);
                    showAlert(
                        'error',
                        'Kamera Gagal Dibuka',
                        'Pastikan izin kamera aktif dan menggunakan HTTPS'
                    );

                });
        }

        function submitAttendance() {

            if (!latitude || !longitude) {
                showAlert(
                    'warning',
                    'Lokasi Belum Terbaca',
                    'Pastikan GPS aktif dan tunggu beberapa detik'
                );

                return;
            }

            let shift = document.getElementById('shift').value;
            if (!shift) {
                showAlert(
                    'info',
                    'Shift Belum Dipilih',
                    'Silakan pilih shift kerja terlebih dahulu'
                );

                return;
            }

            const canvas = document.getElementById('canvas');
            const video = document.getElementById('video');
            canvas.width = video.videoWidth;
            canvas.height = video.videoHeight;

            const ctx = canvas.getContext('2d');
            ctx.drawImage(video, 0, 0);

            canvas.toBlob(blob => {
                let formData = new FormData();
                formData.append('photo', blob, 'absen.jpg');
                formData.append('latitude', latitude);
                formData.append('longitude', longitude);
                formData.append('work_shift_id', shift);
                formData.append('type', document.getElementById('type').value);
                formData.append('_token', '{{ csrf_token() }}');

                Swal.fire({
                    title: 'Mengirim Absensi...',
                    text: 'Mohon tunggu sebentar',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                fetch("{{ route('attendance.store') }}", {
                        method: 'POST',
                        body: formData
                    })
                    .then(res => res.json())
                    .then(res => {
                        let icon = 'success';
                        let title = 'Berhasil';
                        let buttonColor = '#1cc88a';

                        // ❌ Jika di luar radius kantor
                        if (res.message === 'Di luar radius kantor') {
                            icon = 'error';
                            title = 'Gagal Absensi';
                            buttonColor = '#e74a3b';
                        }

                        Swal.fire({
                            icon: icon,
                            title: title,
                            text: res.message,
                            confirmButtonColor: buttonColor
                        }).then(() => {
                            // reload hanya jika absensi berhasil
                            if (icon === 'success') {
                                location.reload();
                            }
                        });
                    })
                    .catch(err => showAlert(
                        'error',
                        'Gagal',
                        'Terjadi kesalahan saat mengirim absensi'
                    ));
            });
        }
    </script>
@endsection
