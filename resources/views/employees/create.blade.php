@extends('master')

@section('content')
    <div class="container-fluid">
        <div class="card shadow-sm">
            <div class="card-body">

                <h4 class="text-primary mb-4">
                    <strong>Tambah Pegawai</strong>
                </h4>

                <form action="{{ route('employees.store') }}" method="POST">
                    @csrf

                    <div class="form-group mb-3">
                        <label class="form-label">Nama Pegawai</label>
                        <input type="text" name="employee_name" class="form-control" placeholder="Masukkan nama pegawai"
                            required>
                    </div>

                    <div class="form-group mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" placeholder="Masukkan email" required>
                    </div>

                    <div class="form-group mb-3">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-control" required>
                            <option value="">-- Pilih Status --</option>
                            <option value="PNS">PNS</option>
                            <option value="PPNPN">PPNPN</option>
                        </select>
                    </div>

                    <div class="form-group mb-4">
                        <div class="form-check">
                            <input type="checkbox" name="is_active" class="form-check-input" id="is_active" checked>
                            <label class="form-check-label" for="is_active">
                                Pegawai Aktif
                            </label>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('employees.index') }}" class="btn btn-secondary">
                            Kembali
                        </a>

                        <button type="submit" class="btn btn-primary">
                            Simpan
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
@endsection
