<!DOCTYPE html>
<html>

<head>
    <title>Tambah Pegawai</title>
</head>

<body>

    <h2>Tambah Pegawai</h2>

    <form action="{{ route('employees.store') }}" method="POST">
        @csrf

        <label>Nama Pegawai</label><br>
        <input type="text" name="employee_name"><br><br>

        <label>Email</label><br>
        <input type="email" name="email"><br><br>

        <label>Status</label><br>
        <select name="status">
            <option value="PNS">PNS</option>
            <option value="PPNPN">PPNPN</option>
        </select><br><br>

        <label>
            <input type="checkbox" name="is_active" checked> Aktif
        </label><br><br>

        <button type="submit">Simpan</button>
    </form>

    <a href="{{ route('employees.index') }}">Kembali</a>

</body>

</html>
