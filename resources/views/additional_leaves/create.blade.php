@extends('master')

@section('content')
    <div class="container-fluid">
        <div class="card shadow-sm mb-2">
            <div class="card-header py-3">
                <h5 class="m-0 font-weight-bold text-primary">Tambah Cuti Tambahan</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('additional-leaves.store') }}" method="POST">
                    @csrf

                    <div class="form-group">
                        <label>Pegawai</label>
                        <select name="employee_id" class="form-control" required>
                            <option value="">-- Pilih Pegawai --</option>
                            @foreach ($employees as $employee)
                                <option value="{{ $employee->id }}">{{ $employee->employee_name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Tahun</label>
                        <input type="number" name="year" class="form-control" value="{{ date('Y') }}" required>
                    </div>

                    <div class="form-group">
                        <label>Sisa Kuota</label>
                        <input type="number" name="remaining_quota" class="form-control" required>
                    </div>

                    <button class="btn btn-success">Simpan</button>
                    <a href="{{ route('additional-leaves.index') }}" class="btn btn-secondary">Kembali</a>
                </form>
            </div>
        </div>
    </div>
@endsection
