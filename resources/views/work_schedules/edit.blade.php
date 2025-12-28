@extends('master')

@section('content')
    <div class="container-fluid">
        <div class="card shadow-sm">
            <div class="card-header py-3">
                <h5 class="m-0 font-weight-bold text-primary">Edit Data Edit Jadwal Kerja</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('work-schedules.update', $workSchedule->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label>Pegawai</label>
                        <select name="employee_id" class="form-control">
                            @foreach ($employees as $employee)
                                <option value="{{ $employee->id }}"
                                    {{ $workSchedule->employee_id == $employee->id ? 'selected' : '' }}>
                                    {{ $employee->employee_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label>Jam Masuk</label>
                        <input type="time" name="time_in" class="form-control" value="{{ $workSchedule->time_in }}">
                    </div>

                    <div class="mb-3">
                        <label>Jam Pulang</label>
                        <input type="time" name="time_out" class="form-control" value="{{ $workSchedule->time_out }}">
                    </div>

                    <div class="mb-3">
                        <label>Zona Waktu</label>
                        <input type="text" name="timezone" class="form-control" value="{{ $workSchedule->timezone }}">
                    </div>

                    <button class="btn btn-primary">Update</button>
                    <a href="{{ route('work-schedules.index') }}" class="btn btn-secondary">Kembali</a>
                </form>
            </div>
        </div>
    </div>
@endsection
