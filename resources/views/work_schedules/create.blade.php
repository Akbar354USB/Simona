@extends('master')

@section('content')
    <div class="container-fluid">
        <div class="card shadow-sm">
            <div class="card-header py-3">
                <h5 class="m-0 font-weight-bold text-primary">Tambah Jadwal Reminder</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('work-schedules.store') }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label>Pegawai</label>
                        <select name="employee_id" class="form-control" required>
                            <option value="">-- Pilih Pegawai --</option>
                            @foreach ($employees as $employee)
                                <option value="{{ $employee->id }}">{{ $employee->employee_name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label>Jam Masuk</label>
                        <input type="time" name="time_in" class="form-control" value="09:00">
                    </div>

                    <div class="mb-3">
                        <label>Jam Pulang</label>
                        <input type="time" name="time_out" class="form-control" value="16:00">
                    </div>

                    <div class="mb-3">
                        <label>Zona Waktu</label>
                        <input type="text" name="timezone" class="form-control" value="Asia/Makassar">
                    </div>

                    <button class="btn btn-success">Simpan</button>
                    <a href="{{ route('work-schedules.index') }}" class="btn btn-secondary">Kembali</a>
                </form>
            </div>
        </div>
    </div>
@endsection
