@extends('master')

@section('content')
    <div class="container-fluid">
        <div class="card shadow-sm">
            <div class="card-header py-3">
                <h5 class="m-0 font-weight-bold text-primary">
                    Edit Pengajuan Cuti Tambahan
                </h5>
            </div>

            <div class="card-body">
                <form action="{{ route('additional-leave-requests.update', $additionalLeaveRequest->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    {{-- ALASAN CUTI --}}
                    <label class="font-weight-bold">Alasan Cuti</label>
                    <textarea name="leave_reason" class="form-control mb-3" rows="3">{{ $additionalLeaveRequest->leave_reason }}</textarea>

                    {{-- PERIODE CUTI --}}
                    <label class="font-weight-bold">Waktu Cuti</label>

                    @foreach ($additionalLeaveRequest->periods as $index => $period)
                        <div class="border rounded p-3 mb-3">
                            <strong>Periode {{ $index + 1 }}</strong>

                            <input type="hidden" name="periods[{{ $index }}][id]" value="{{ $period->id }}">

                            <div class="row mt-2">
                                <div class="col-md-6">
                                    <label>Tanggal Mulai</label>
                                    <input type="date" name="periods[{{ $index }}][start_date]"
                                        class="form-control" value="{{ $period->start_date }}">
                                </div>

                                <div class="col-md-6">
                                    <label>Tanggal Selesai</label>
                                    <input type="date" name="periods[{{ $index }}][end_date]" class="form-control"
                                        value="{{ $period->end_date }}">
                                </div>
                            </div>
                        </div>
                    @endforeach

                    {{-- NO TELP --}}
                    <label class="font-weight-bold">No. Telp</label>
                    <input type="text" name="phone" class="form-control mb-3"
                        value="{{ $additionalLeaveRequest->phone }}">

                    {{-- ALAMAT CUTI --}}
                    <label class="font-weight-bold">Alamat Selama Cuti</label>
                    <textarea name="leave_address" class="form-control mb-3" rows="2">{{ $additionalLeaveRequest->leave_address }}</textarea>

                    <button class="btn btn-primary">
                        <i class="fas fa-save"></i> Update
                    </button>
                </form>
            </div>
        </div>
    </div>
@endsection
