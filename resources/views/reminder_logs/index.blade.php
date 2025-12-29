@extends('master')

@section('content')
    <div class="container-fluid">

        {{-- CARD --}}
        <div class="card shadow-sm">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h5 class="m-0 font-weight-bold text-primary">
                    Monitoring Reminder Absensi
                </h5>

                {{-- TOMBOL HAPUS SEMUA --}}
                <form action="{{ route('reminder-logs.truncate') }}" method="POST"
                    onsubmit="return confirm('Yakin ingin menghapus SEMUA data reminder log?')">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-sm btn-danger">
                        <i class="fas fa-trash"></i> Hapus Semua
                    </button>
                </form>
            </div>

            {{-- ALERT --}}
            @if (session('success'))
                <div class="alert alert-success m-3">
                    {{ session('success') }}
                </div>
            @endif

            <div class="card-body">

                {{-- FILTER & SEARCH --}}
                <form method="GET" action="{{ route('reminder-logs.index') }}" class="mb-3">
                    <div class="row g-2">

                        {{-- SEARCH --}}
                        <div class="col-md-3 mb-1">
                            <input type="text" name="search" class="form-control"
                                placeholder="Cari nama pegawai / pesan..." value="{{ request('search') }}">
                        </div>

                        {{-- FILTER EVENT --}}
                        <div class="col-md-2 mb-1">
                            <select name="event_type" class="form-control">
                                <option value="">-- Event --</option>
                                <option value="IN" {{ request('event_type') == 'IN' ? 'selected' : '' }}>IN</option>
                                <option value="OUT" {{ request('event_type') == 'OUT' ? 'selected' : '' }}>OUT</option>
                            </select>
                        </div>

                        {{-- FILTER STATUS --}}
                        <div class="col-md-2 mb-1">
                            <select name="status" class="form-control">
                                <option value="">-- Status --</option>
                                <option value="success" {{ request('status') == 'success' ? 'selected' : '' }}>Success
                                </option>
                                <option value="failed" {{ request('status') == 'failed' ? 'selected' : '' }}>Failed</option>
                            </select>
                        </div>

                        {{-- FILTER TANGGAL --}}
                        <div class="col-md-2 mb-1">
                            <input type="date" name="event_date" class="form-control"
                                value="{{ request('event_date') }}">
                        </div>

                        {{-- BUTTON --}}
                        <div class="col-md-3 d-flex gap-2">
                            <button class="btn btn-primary mr-2">
                                <i class="fas fa-filter"></i> Filter
                            </button>

                            <a href="{{ route('reminder-logs.index') }}" class="btn btn-secondary">
                                <i class="fas fa-sync"></i> Reset
                            </a>
                        </div>

                    </div>
                </form>

                {{-- TABLE --}}
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>No</th>
                                <th>Pegawai</th>
                                <th>Event</th>
                                <th>Tanggal</th>
                                <th>Status</th>
                                <th>Pesan</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($logs as $log)
                                <tr>
                                    <td>
                                        {{ $loop->iteration + ($logs->currentPage() - 1) * $logs->perPage() }}
                                    </td>
                                    <td>{{ $log->employee->employee_name ?? '-' }}</td>
                                    <td>
                                        <span class="badge bg-info">{{ $log->event_type }}</span>
                                    </td>
                                    <td>{{ $log->event_date }}</td>
                                    <td>
                                        @if ($log->status === 'success')
                                            <span class="badge bg-success">Success</span>
                                        @else
                                            <span class="badge bg-danger">Failed</span>
                                        @endif
                                    </td>
                                    <td>{{ $log->message ?? '-' }}</td>
                                    <td>
                                        <form action="{{ route('reminder-logs.destroy', $log->id) }}" method="POST"
                                            onsubmit="return confirm('Yakin ingin menghapus data ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-sm btn-danger">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center">
                                        Data tidak ditemukan
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- PAGINATION --}}
                {{ $logs->withQueryString()->links() }}

            </div>
        </div>
    </div>
@endsection
