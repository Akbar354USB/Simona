@extends('master')

@section('content')
    <div class="container-fluid">

        <div class="card shadow-sm">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h5 class="m-0 font-weight-bold text-primary">
                    📋 Data Absensi Pegawai
                </h5>
            </div>

            <div class="card-body">

                @if (session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif

                <div class="table-responsive">
                    <table class="table table-bordered table-hover text-center">
                        <thead class="thead-light">
                            <tr>
                                <th>No</th>
                                <th>Nama Pegawai</th>
                                <th>Tanggal</th>
                                <th>Jenis</th>
                                <th>Shift</th>
                                <th>Jam</th>
                                <th>Jarak (m)</th>
                                <th>Lokasi</th>
                                <th>Foto</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($attendances as $attendance)
                                <tr>
                                    <td>{{ $loop->iteration + $attendances->firstItem() - 1 }}</td>

                                    <td>
                                        {{ $attendance->employee->employee_name ?? '-' }}
                                    </td>

                                    <td>
                                        {{ \Carbon\Carbon::parse($attendance->attendance_date)->translatedFormat('d M Y') }}
                                    </td>

                                    <td>
                                        <span
                                            class="badge 
                                        {{ $attendance->type == 'DATANG' ? 'badge-success' : 'badge-warning' }}">
                                            {{ $attendance->type }}
                                        </span>
                                    </td>

                                    <td>
                                        {{ $attendance->workShift->shift_name ?? '-' }}
                                    </td>

                                    <td>
                                        {{ \Carbon\Carbon::parse($attendance->attendance_time)->format('H:i:s') }}
                                    </td>

                                    <td>
                                        {{ $attendance->distance_meter }} m
                                    </td>

                                    <td>
                                        <a href="https://www.google.com/maps?q={{ $attendance->latitude }},{{ $attendance->longitude }}"
                                            target="_blank" class="btn btn-sm btn-info">
                                            📍 Maps
                                        </a>
                                    </td>

                                    <td>
                                        <button class="btn btn-sm btn-info" data-toggle="modal"
                                            data-target="#photoModal{{ $attendance->id }}">
                                            📷 Lihat Foto
                                        </button>
                                    </td>


                                    <td>
                                        <form action="{{ route('attendances.destroy', $attendance->id) }}" method="POST"
                                            onsubmit="return confirm('Yakin ingin menghapus data ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-sm btn-danger">
                                                🗑 Hapus
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                <!-- Modal Foto Absensi -->
                                <div class="modal fade" id="photoModal{{ $attendance->id }}" tabindex="-1">
                                    <div class="modal-dialog modal-dialog-centered modal-lg">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">
                                                    Foto Absensi - {{ $attendance->employee->name ?? '-' }}
                                                </h5>
                                                <button type="button" class="close" data-dismiss="modal">
                                                    <span>&times;</span>
                                                </button>
                                            </div>

                                            <div class="modal-body text-center">
                                                <img src="{{ asset('storage/' . $attendance->photo_path) }}"
                                                    class="img-fluid rounded">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <tr>
                                    <td colspan="10">Data absensi belum tersedia</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-3">
                    {{ $attendances->links() }}
                </div>

            </div>
        </div>

    </div>
@endsection
