@extends('master')

@section('content')
    <div class="container-fluid">
        <div class="card shadow-sm">
            <div class="card-header py-3">
                <h5 class="m-0 font-weight-bold text-primary">Tambah Unit Kerja</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('work-units.store') }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label>Unit Kerja</label>
                        <input type="text" name="work_unit" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label>Nama Pimpinan</label>
                        <input type="text" name="leader_name" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label>NIP Pimpinan</label>
                        <input type="text" name="leader_nip" class="form-control" required>
                    </div>

                    <button class="btn btn-success">Save</button>
                    <a href="{{ route('work-units.index') }}" class="btn btn-secondary">Back</a>
                </form>
            </div>
        </div>
    </div>
@endsection
