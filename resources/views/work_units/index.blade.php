@extends('master')

@section('content')
    <div class="container-fluid">
        <div class="card shadow-sm">
            <div class="card-header py-3">
                <h5 class="m-0 font-weight-bold text-primary">Unit Kerja</h5>
            </div>

            <div class="card-body">
                <a href="{{ route('work-units.create') }}" class="btn btn-primary mb-3">
                    + Unit Kerja
                </a>
                <div class="table table-bordered table-striped table-hover">
                    <table class="table table-bordered">
                        <thead>
                            <tr class="text-center">
                                <th>No</th>
                                <th>Unit Kerja</th>
                                <th>Nama Pimpinan</th>
                                <th>NIP Pimpinan</th>
                                <th width="150">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($workUnits as $item)
                                <tr class="text-center">
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $item->work_unit }}</td>
                                    <td>{{ $item->leader_name }}</td>
                                    <td>{{ $item->leader_nip }}</td>
                                    <td>
                                        <form action="{{ route('work-units.destroy', $item->id) }}" method="POST"
                                            class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-danger btn-sm"
                                                onclick="return confirm('Delete this data?')">
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
