@extends('master')

@section('content')
    <div class="container-fluid">
        <div class="card shadow-sm mb-2">
            <div class="card-header py-3">
                <h5 class="m-0 font-weight-bold text-primary">📸 Absensi Pegawai</h5>
            </div>
            <div class="card-body">
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
            alert('GPS tidak aktif');
        });

        // 📷 Kamera (realtime only)
        function openCamera() {

            if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
                alert('Browser tidak mendukung kamera');
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
                    alert('Kamera tidak bisa dibuka. Pastikan izin kamera aktif & HTTPS');
                });
        }

        function submitAttendance() {

            if (!latitude || !longitude) {
                alert('Lokasi belum terbaca');
                return;
            }

            let shift = document.getElementById('shift').value;
            if (!shift) {
                alert('Pilih shift terlebih dahulu');
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

                fetch("{{ route('attendance.store') }}", {
                        method: 'POST',
                        body: formData
                    })
                    .then(res => res.json())
                    .then(res => {
                        alert(res.message);
                        location.reload();
                    })
                    .catch(err => alert('Gagal absensi'));
            });
        }
    </script>
@endsection

{{-- @extends('master') --}}

{{-- @section('css')
    <style>
        .camera-box {
            width: 100%;
            aspect-ratio: 3 / 4;
            background: #000;
        }

        video {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .rounded-4 {
            border-radius: 1rem;
        }
    </style>
@endsection --}}

{{-- @section('content')
    <div class="container px-2">
        <div class="card shadow-sm rounded-4">


            <div class="card-header text-center bg-primary text-white rounded-top-4 py-3">
                <h5 class="mb-0">📸 ABSENSI PEGAWAI</h5>
                <small id="clock" class="d-block mt-1"></small>
            </div>

            <div class="card-body">


                <div class="mb-3">
                    <label class="fw-bold mb-1">1️⃣ Pilih Shift Kerja</label>
                    <select id="shift" class="form-select form-select-lg">
                        <option value="">-- Pilih Shift --</option>
                        @foreach ($shifts as $shift)
                            <option value="{{ $shift->id }}">
                                {{ $shift->shift_name }} ({{ $shift->start_time }} - {{ $shift->end_time }})
                            </option>
                        @endforeach
                    </select>
                </div>


                <div class="mb-3">
                    <label class="fw-bold mb-1">2️⃣ Jenis Absensi</label>
                    <div class="btn-group w-100">
                        <button class="btn btn-outline-success btn-lg" onclick="setType('DATANG')">🟢 Datang</button>
                        <button class="btn btn-outline-danger btn-lg" onclick="setType('PULANG')">🔴 Pulang</button>
                    </div>
                    <input type="hidden" id="type" value="DATANG">
                </div>


                <div class="mb-3">
                    <label class="fw-bold mb-1">3️⃣ Foto Kehadiran</label>
                    <div class="camera-box border rounded-3 overflow-hidden mb-2">
                        <video id="video" autoplay playsinline></video>
                        <canvas id="canvas" class="d-none"></canvas>
                    </div>

                    <button class="btn btn-primary btn-lg w-100" onclick="openCamera()">
                        📷 Buka Kamera
                    </button>
                </div>


                <button class="btn btn-success btn-lg w-100 mt-3" onclick="submitAttendance()">
                    ✅ Konfirmasi Absensi
                </button>

            </div>
        </div>
    </div>
@endsection --}}
{{-- @section('js')
    <script>
        let latitude = null;
        let longitude = null;
        let stream = null;

        // Jam realtime
        setInterval(() => {
            document.getElementById('clock').innerText =
                new Date().toLocaleString('id-ID');
        }, 1000);

        // GPS
        navigator.geolocation.getCurrentPosition(pos => {
            latitude = pos.coords.latitude;
            longitude = pos.coords.longitude;
        }, err => {
            alert('GPS tidak aktif');
        });

        // Pilih datang/pulang
        function setType(type) {
            document.getElementById('type').value = type;
        }

        // Kamera
        function openCamera() {
            navigator.mediaDevices.getUserMedia({
                video: {
                    facingMode: 'user'
                },
                audio: false
            }).then(s => {
                stream = s;
                document.getElementById('video').srcObject = s;
            }).catch(() => {
                alert('Kamera tidak bisa dibuka');
            });
        }

        // Submit
        function submitAttendance() {

            if (!latitude || !longitude) {
                alert('Lokasi belum terbaca');
                return;
            }

            const shift = document.getElementById('shift').value;
            if (!shift) {
                alert('Pilih shift terlebih dahulu');
                return;
            }

            const canvas = document.getElementById('canvas');
            const video = document.getElementById('video');
            canvas.width = video.videoWidth;
            canvas.height = video.videoHeight;

            canvas.getContext('2d').drawImage(video, 0, 0);

            canvas.toBlob(blob => {
                let formData = new FormData();
                formData.append('photo', blob, 'absen.jpg');
                formData.append('latitude', latitude);
                formData.append('longitude', longitude);
                formData.append('work_shift_id', shift);
                formData.append('type', document.getElementById('type').value);
                formData.append('_token', '{{ csrf_token() }}');

                fetch("{{ route('attendance.store') }}", {
                        method: 'POST',
                        body: formData
                    })
                    .then(res => res.json())
                    .then(res => {
                        alert(res.message);
                        location.reload();
                    })
                    .catch(() => alert('Gagal absensi'));
            });
        }
    </script>
@endsection --}}
