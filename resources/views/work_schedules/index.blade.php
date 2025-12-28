@extends('master')

@section('content')
    <div class="container-fluid">
        <div class="card shadow-sm">
            <div class="card-header py-3">
                <h5 class="m-0 font-weight-bold text-primary">Data Waktu Reminder Pegawai</h5>
            </div>
            <div class="card-body">
                <a href="{{ route('work-schedules.create') }}" class="btn btn-primary mb-3">
                    Tambah Jadwal
                </a>
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead class="table-light">
                            <tr>
                                <th>No</th>
                                <th>Pegawai</th>
                                <th>Jam Masuk</th>
                                <th>Jam Pulang</th>
                                <th>Zona Waktu</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($schedules as $schedule)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $schedule->employee->employee_name }}</td>
                                    <td>{{ $schedule->time_in }}</td>
                                    <td>{{ $schedule->time_out }}</td>
                                    <td>{{ $schedule->timezone }}</td>
                                    <td>
                                        <a href="{{ route('work-schedules.edit', $schedule->id) }}"
                                            class="btn btn-warning btn-sm"><i class="fas fa-edit"></i></a>

                                        <form action="{{ route('work-schedules.destroy', $schedule->id) }}" method="POST"
                                            class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button onclick="return confirm('Yakin hapus?')" class="btn btn-danger btn-sm">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
