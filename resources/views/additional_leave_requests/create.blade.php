@extends('master')

@section('content')
    <div class="container-fluid">
        <div class="card shadow-sm mb-2">
            <div class="card-header py-3">
                <h5 class="m-0 font-weight-bold text-primary">Ajukan Cuti Tambahan</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('additional-leave-requests.store') }}" method="POST">
                    @csrf

                    <input type="text" name="employee_name" class="form-control mb-2" placeholder="Nama Pegawai">
                    <input type="text" name="nip" class="form-control mb-2" placeholder="NIP">
                    <input type="text" name="position" class="form-control mb-2" placeholder="Jabatan">
                    <input type="text" name="length_of_service" class="form-control mb-2" placeholder="Masa Kerja">
                    {{-- <input type="text" name="work_unit" class="form-control mb-2" placeholder="Unit Kerja"> --}}
                    <select name="work_unit_id" class="form-control mb-2" required>
                        <option value="">-- Pilih Unit Kerja --</option>
                        @foreach ($workUnits as $unit)
                            <option value="{{ $unit->id }}">
                                {{ $unit->work_unit }}
                            </option>
                        @endforeach
                    </select>

                    <textarea name="leave_reason" class="form-control mb-2" placeholder="Alasan Cuti"></textarea>
                    <label for="">Waktu Cuti</label>

                    <div id="period-wrapper">

                        {{-- PERIODE PERTAMA (DEFAULT) --}}
                        <div class="border rounded p-3 mb-3 period-item">
                            <strong>Periode 1</strong>

                            <div class="row mt-2">
                                <div class="col-md-6">
                                    <label>Tanggal Mulai</label>
                                    <input type="date" name="periods[0][start_date]" class="form-control">
                                </div>

                                <div class="col-md-6">
                                    <label>Tanggal Selesai</label>
                                    <input type="date" name="periods[0][end_date]" class="form-control">
                                </div>
                            </div>
                        </div>

                    </div>
                    <button type="button" id="add-period" class="btn btn-outline-primary btn-sm mb-2">
                        <i class="fas fa-plus"></i> Tambah Periode
                    </button>


                    <input type="text" name="phone" class="form-control mb-2" placeholder="No Telp">
                    <textarea name="leave_address" class="form-control mb-2" placeholder="Alamat Selama Cuti"></textarea>

                    <button type="submit" class="btn btn-success">Simpan</button>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script>
        let periodIndex = 1;
        let maxPeriod = 2;

        document.getElementById('add-period').addEventListener('click', function() {

            if (periodIndex >= maxPeriod) {
                return;
            }

            const wrapper = document.getElementById('period-wrapper');

            const html = `
            <div class="border rounded p-3 mb-3 period-item">
                <strong>Periode ${periodIndex + 1}</strong>

                <div class="row mt-2">
                    <div class="col-md-6">
                        <label>Tanggal Mulai</label>
                        <input type="date" name="periods[${periodIndex}][start_date]" class="form-control">
                    </div>

                    <div class="col-md-6">
                        <label>Tanggal Selesai</label>
                        <input type="date" name="periods[${periodIndex}][end_date]" class="form-control">
                    </div>
                </div>
            </div>
        `;

            wrapper.insertAdjacentHTML('beforeend', html);
            periodIndex++;

            // 🔒 Disable tombol setelah 2 periode
            this.disabled = true;
            this.classList.add('disabled');
            this.innerText = 'Periode maksimal tercapai';
        });
    </script>
    @if ($errors->any())
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Pengajuan Cuti Gagal',
                html: `
                <ul style="text-align:left;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            `,
                confirmButtonText: 'Mengerti'
            });
        </script>
    @endif
@endsection
