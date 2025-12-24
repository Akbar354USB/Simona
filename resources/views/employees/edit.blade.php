@extends('master')

@section('content')
    <div class="container-fluid">
        <div class="card shadow-sm">
            <div class="card-body">

                <h4 class="text-primary mb-4">
                    <strong>Edit Pegawai</strong>
                </h4>

                <form action="{{ route('employees.update', $employee->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="form-group mb-3">
                        <label class="form-label">Nama Pegawai</label>
                        <input type="text" name="employee_name" class="form-control" value="{{ $employee->employee_name }}"
                            required>
                    </div>

                    <div class="form-group mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" value="{{ $employee->email }}" required>
                    </div>

                    <div class="form-group mb-3">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-control" required>
                            <option value="PNS" {{ $employee->status == 'PNS' ? 'selected' : '' }}>
                                PNS
                            </option>
                            <option value="PPNPN" {{ $employee->status == 'PPNPN' ? 'selected' : '' }}>
                                PPNPN
                            </option>
                        </select>
                    </div>

                    <div class="form-group mb-4">
                        <div class="form-check">
                            <input type="checkbox" name="is_active" class="form-check-input" id="is_active"
                                {{ $employee->is_active ? 'checked' : '' }}>
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
                            Update
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
