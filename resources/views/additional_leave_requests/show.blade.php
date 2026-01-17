@extends('master')

@section('content')
    <div class="container-fluid">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-primary text-white d-flex align-items-center">
                <i class="fas fa-eye mr-2"></i>
                <h5 class="mb-0">Detail Pengajuan Cuti Tambahan</h5>
            </div>

            <div class="card-body">

                {{-- NAMA --}}
                <div class="row mb-3">
                    <div class="col-md-4 text-muted font-weight-bold">Nama Pegawai</div>
                    <div class="col-md-8">
                        {{ $additionalLeaveRequest->employee_name }}
                    </div>
                </div>

                {{-- NIP --}}
                <div class="row mb-3">
                    <div class="col-md-4 text-muted font-weight-bold">NIP</div>
                    <div class="col-md-8">
                        {{ $additionalLeaveRequest->nip }}
                    </div>
                </div>

                {{-- ALASAN --}}
                <div class="row mb-3">
                    <div class="col-md-4 text-muted font-weight-bold">Alasan Cuti</div>
                    <div class="col-md-8">
                        {{ $additionalLeaveRequest->leave_reason }}
                    </div>
                </div>

                {{-- WAKTU CUTI (MULTI PERIODE) --}}
                <div class="row mb-3">
                    <div class="col-md-4 text-muted font-weight-bold">Waktu Cuti</div>
                    <div class="col-md-8">
                        @foreach ($additionalLeaveRequest->periods as $index => $period)
                            <div class="mb-2">
                                <span class="badge badge-info">
                                    Periode {{ $index + 1 }}
                                </span>
                                {{ \Carbon\Carbon::parse($period->start_date)->translatedFormat('d F Y') }}
                                s/d
                                {{ \Carbon\Carbon::parse($period->end_date)->translatedFormat('d F Y') }}
                                <small class="text-muted">
                                    ({{ $period->total_days }} hari)
                                </small>
                            </div>
                        @endforeach
                    </div>
                </div>

                {{-- TOTAL HARI CUTI (OPSIONAL) --}}
                <div class="row mb-3">
                    <div class="col-md-4 text-muted font-weight-bold">Total Hari Cuti</div>
                    <div class="col-md-8">
                        {{ $additionalLeaveRequest->periods->sum('total_days') }} hari
                    </div>
                </div>

                {{-- NOMOR SURAT --}}
                <div class="row mb-4">
                    <div class="col-md-4 text-muted font-weight-bold">Nomor Surat</div>
                    <div class="col-md-8">
                        {{ $additionalLeaveRequest->letter_number ?? '-' }}
                    </div>
                </div>

                <hr>

                <div class="d-flex justify-content-between">
                    <a href="{{ route('additional-leave-requests.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>

                    <a href="{{ route('additional-leave-requests.edit', $additionalLeaveRequest->id) }}"
                        class="btn btn-warning">
                        <i class="fas fa-edit"></i> Edit
                    </a>
                </div>

            </div>
        </div>
    </div>
@endsection
