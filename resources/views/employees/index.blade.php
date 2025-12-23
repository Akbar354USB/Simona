@extends('master')

@section('content')
    <div class="container-fluid card card-body">
        <h4><strong>Data Pegawai</strong></h4>

        <a href="{{ route('employees.create') }}"><button type="button" class="btn btn-primary mb-3">+ Tambah Data
                Pegawai</button></a>

        @if (session('success'))
            <p style="color: green">{{ session('success') }}</p>
        @endif

        {{-- <table border="1" cellpadding="10"> --}}
        <table class="table table-bordered">
            <tr>
                <th>No</th>
                <th>Nama</th>
                <th>Email</th>
                <th>Status</th>
                <th>Aktif</th>
                <th>Aksi</th>
            </tr>
            @foreach ($employees as $employee)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $employee->employee_name }}</td>
                    <td>{{ $employee->email }}</td>
                    <td>{{ $employee->status }}</td>
                    <td>{{ $employee->is_active ? 'Ya' : 'Tidak' }}</td>
                    <td>
                        <a href="{{ route('employees.edit', $employee->id) }}"><button type="button"
                                class="btn btn-info">Edit</button></a>
                        <form action="{{ route('employees.destroy', $employee->id) }}" method="POST" style="display:inline">
                            @csrf
                            @method('DELETE')
                            <button onclick="return confirm('Hapus data?')">Hapus</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </table>
    </div>
@endsection
