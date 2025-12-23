<!DOCTYPE html>
<html>

<head>
    <title>Edit Pegawai</title>
</head>

<body>

    <h2>Edit Pegawai</h2>

    <form action="{{ route('employees.update', $employee->id) }}" method="POST">
        @csrf
        @method('PUT')

        <label>Nama Pegawai</label><br>
        <input type="text" name="employee_name" value="{{ $employee->employee_name }}"><br><br>

        <label>Email</label><br>
        <input type="email" name="email" value="{{ $employee->email }}"><br><br>

        <label>Status</label><br>
        <select name="status">
            <option value="PNS" {{ $employee->status == 'PNS' ? 'selected' : '' }}>PNS</option>
            <option value="PPNPN" {{ $employee->status == 'PPNPN' ? 'selected' : '' }}>PPNPN</option>
        </select><br><br>

        <label>
            <input type="checkbox" name="is_active" {{ $employee->is_active ? 'checked' : '' }}> Aktif
        </label><br><br>

        <button type="submit">Update</button>
    </form>

    <a href="{{ route('employees.index') }}">Kembali</a>

</body>

</html>
