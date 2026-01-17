@extends('master')

@section('content')
    <div class="container-fluid">
        <div class="card shadow-sm">
            <div class="card-header py-3">
                <h5 class="m-0 font-weight-bold text-primary">Data Pengajuan Cuti Tambahan</h5>
            </div>

            <div class="card-body">
                <a href="{{ route('additional-leave-requests.create') }}" class="btn btn-primary mb-3">
                    + Pengajuan Cuti Tambahan
                </a>

                {{-- FILTER & SEARCH --}}
                <div class="card border mb-4">
                    <div class="card-body p-3">
                        <form method="GET" action="{{ route('additional-leave-requests.index') }}">
                            <div class="form-row align-items-end">

                                <div class="col-md-4">
                                    <label class="small font-weight-bold">
                                        <i class="fas fa-search"></i> Kata Kunci
                                    </label>
                                    <input type="text" name="keyword" class="form-control"
                                        placeholder="Nama / NIP / No Surat" value="{{ request('keyword') }}">
                                </div>

                                <div class="col-md-3">
                                    <label class="small font-weight-bold">
                                        <i class="fas fa-calendar"></i> Tanggal Mulai
                                    </label>
                                    <input type="date" name="start_date" class="form-control"
                                        value="{{ request('start_date') }}">
                                </div>

                                <div class="col-md-3">
                                    <label class="small font-weight-bold">
                                        <i class="fas fa-calendar-check"></i> Tanggal Selesai
                                    </label>
                                    <input type="date" name="end_date" class="form-control"
                                        value="{{ request('end_date') }}">
                                </div>

                                <div class="col-md-2 d-flex">
                                    <button class="btn btn-primary w-100 mr-1">
                                        <i class="fas fa-search"></i>
                                    </button>

                                    <a href="{{ route('additional-leave-requests.index') }}"
                                        class="btn btn-outline-secondary w-100">
                                        <i class="fas fa-sync"></i>
                                    </a>
                                </div>

                            </div>
                        </form>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover">
                        <thead>
                            <tr class="table-light text-center">
                                <th>Nama</th>
                                <th>NIP</th>
                                <th>Waktu Cuti</th>
                                <th>No Surat</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($requests as $item)
                                <tr class="text-center">
                                    <td>{{ $item->employee_name }}</td>
                                    <td>{{ $item->nip }}</td>

                                    {{-- WAKTU CUTI MULTI PERIODE --}}
                                    <td>
                                        @foreach ($item->periods as $period)
                                            <span class="badge badge-success d-block mb-1">
                                                {{ \Carbon\Carbon::parse($period->start_date)->format('d M Y') }}
                                                –
                                                {{ \Carbon\Carbon::parse($period->end_date)->format('d M Y') }}
                                                <small>({{ $period->total_days }} hari)</small>
                                            </span>
                                        @endforeach
                                    </td>

                                    <td>{{ $item->letter_number }}</td>

                                    <td>
                                        <a href="{{ route('additional-leave-requests.show', $item->id) }}"
                                            class="btn btn-info btn-sm">
                                            <i class="fas fa-eye"></i>
                                        </a>

                                        <a href="{{ route('additional-leave-requests.edit', $item->id) }}"
                                            class="btn btn-warning btn-sm">
                                            <i class="fas fa-edit"></i>
                                        </a>

                                        <a href="{{ route('additional-leave-requests.print', $item->id) }}" target="_blank"
                                            class="btn btn-secondary btn-sm" title="Cetak PDF">
                                            <i class="fas fa-file-pdf"></i>
                                        </a>

                                        <form action="{{ route('additional-leave-requests.destroy', $item->id) }}"
                                            method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button onclick="return confirm('Hapus data?')" class="btn btn-danger btn-sm">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted">
                                        Belum ada pengajuan cuti
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    @if (session('success'))
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Berhasil',
                text: "{{ session('success') }}",
                confirmButtonColor: '#1cc88a'
            });
        </script>
    @endif
@endsection
