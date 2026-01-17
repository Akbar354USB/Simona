<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <style>
        @page {
            margin: 35px 30px 25px 30px;
            /* atas kanan bawah kiri */
        }

        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 11px;
            margin: 0;
            padding: 0;
        }

        /* ================= KOP SURAT ================= */
        .kop-table {
            width: 100%;
            border-collapse: collapse;
            margin: 0;
        }

        .kop-table td {
            border: none !important;
            padding: 0;
            vertical-align: middle;
        }

        .kop-logo {
            width: 97px;
        }

        .kop-text {
            text-align: center;
            font-size: 13px;
            font-weight: bold;
            line-height: 1;
            padding-right: 30px;
        }

        .subtext {
            font-size: 11px;
            font-weight: normal;
            line-height: 0.8;
        }

        hr.garis-tebal {
            border: 2px solid #000;
            margin: 6px 0 12px 0;
        }

        /* ================= ISI SURAT ================= */
        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .bold {
            font-weight: bold;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        td,
        th {
            border: 1px solid #000;
            padding: 2px 4px;
            /* 🔑 RAPAT ATAS–BAWAH */
            line-height: 1.2;
            /* 🔑 TINGGI BARIS IDEAL */
            vertical-align: middle;
        }

        .no-border td {
            border: none;
            padding: 2px;
        }

        .section-title {
            font-weight: bold;
            padding: 3px 4px;
            /* Judul tetap sedikit lega */
        }

        .signature-box {
            height: 90px;
        }
    </style>

</head>

<body>
    <table class="kop-table">
        <tr>
            <td width="15%" class="text-center">
                <img src="{{ public_path('backend/kop.png') }}" class="kop-logo">
            </td>

            <td width="78%" class="kop-text">
                KEMENTERIAN KEUANGAN REPUBLIK INDONESIA <br>
                DIREKTORAT JENDERAL PERBENDAHARAAN <br>
                KANTOR WILAYAH DIREKTORAT JENDERAL PERBENDAHARAAN <br>
                PROVINSI SULAWESI BARAT <br>
                KANTOR PELAYANAN PERBENDAHARAAN NEGARA TIPE A2 MAJENE <br>
                <span class="subtext">
                    Jl. Jenderal Sudirman, Majene 91412; Telp (0422) 21061 <br>
                    Surel: kppnmajene@kemenkeu.go.id | Laman: djpbn.kemenkeu.go.id/kppn/majene
                </span>
            </td>
            <td width="7%"></td>
        </tr>
    </table>

    <hr class="garis-tebal">


    <table class="no-border">
        <tr>
            <td width="70%">
                Kepada Yth.<br>
                Yth. Kepala KPPN Majene
            </td>
            <td class="text-right">
                Majene, {{ now()->locale('id')->translatedFormat('d F Y') }}
            </td>
        </tr>
    </table>

    <br>

    <div class="text-center">
        <strong>SURAT PERMINTAAN DAN PEMBERIAN CUTI</strong> <br> NOMOR : {{ $request->letter_number }}
    </div>
    <br>


    {{-- ================= I. DATA PEGAWAI ================= --}}
    <table>
        <tr>
            <td class="section-title" colspan="4">I. DATA PEGAWAI</td>
        </tr>
        <tr>
            <td width="12%">Nama</td>
            <td width="38%">{{ $request->employee_name }}</td>
            <td width="12%">NIP</td>
            <td width="38%">{{ $request->nip }}</td>
        </tr>
        <tr>
            <td>Jabatan</td>
            <td>{{ $request->position }}</td>
            <td>Masa Kerja </td>
            <td>{{ $request->length_of_service }}</td>
        </tr>
        <tr>
            <td style="vertical-align: top;">Unit Kerja</td>
            <td colspan="3">{{ $request->workUnit->work_unit ?? '-' }}, Kantor Pelayanan Perbendaharaan Negara Tipe
                A2 Majene, Kantor
                Wilayah Direktorat Jenderal Perbendaharaan Provinsi Sulawesi Barat</td>
        </tr>
    </table>
    <br>
    {{-- ================= II. JENIS CUTI ================= --}}
    <table>
        <tr>
            <td class="section-title">II. JENIS CUTI YANG DIAMBIL</td>
        </tr>
        <tr>
            <td style="padding-left: 20px"> 1. Cuti Tahunan Tambahan</td>
        </tr>
    </table>
    <br>
    {{-- ================= III. ALASAN CUTI ================= --}}
    <table>
        <tr>
            <td class="section-title">III. ALASAN CUTI</td>
        </tr>
        <tr>
            <td style="padding-left: 20px">{{ $request->leave_reason }}</td>
        </tr>
    </table>
    <br>
    <table>
        <tr>
            <td class="section-title" colspan="7">IV. LAMANYA CUTI</td>
        </tr>
        @foreach ($request->periods as $i => $period)
            <tr class="text-center">
                <td><strong>{{ $i + 1 }}</strong></td>
                <td>Selama</td>
                <td>{{ $period->total_days }} Hari</td>
                <td>Mulai Tanggal</td>
                <td>{{ \Carbon\Carbon::parse($period->start_date)->format('d-m-Y') }}</td>
                <td>s/d</td>
                <td>{{ \Carbon\Carbon::parse($period->end_date)->format('d-m-Y') }}</td>
            </tr>
        @endforeach
    </table>
    <br>

    <table>
        <tr>
            <td colspan="3" class="section-title">V. CATATAN CUTI</td>
        </tr>

        <tr>
            <td colspan="3" style="padding-left: 20px">1. CUTI TAHUNAN TAMBAHAN</td>
        </tr>

        <tr class="text-center">
            <td width="20%">Tahun</td>
            <td width="20%">Sisa</td>
            <td width="60%">Keterangan</td>
        </tr>

        <tr class="text-center">
            <td>
                {{ $additionalLeave->year ?? '-' }}
            </td>

            <td>
                {{ $additionalLeave->remaining_quota ?? 0 }} hari
            </td>

            <td style="text-align:left;">
                -
            </td>
        </tr>
    </table>
    <br>
    <table>
        <tr>
            <td class="section-title" colspan="3">VI. ALAMAT SELAMA MENJALANKAN CUTI</td>
        </tr>
        <tr>
            <td width="50%">Alamat</td>
            <td width="5%">Telp</td>
            <td>{{ $request->phone }}</td>
        </tr>
        <tr>
            <td style="vertical-align: top;">{{ $request->leave_address }}</td>
            <td colspan="2" style="padding-left: 20px">Hormat Saya,
                <br><br><br><br><br><br>
                <span style="color: #777; font-size: 12px;">
                    Ditandatangani secara elektronik
                </span><br>
                {{ $request->employee_name }} <br>
                NIP. {{ $request->nip }}
            </td>
        </tr>
    </table>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <table>
        <tr>
            <td class="section-title" colspan="4">VII. PERTIMBANGAN ATASAN LANGSUNG (✓)</td>
        </tr>
        <tr>
            <td width="25%" class="text-center">DISETUJUI</td>
            <td width="25%" class="text-center">PERUBAHAN</td>
            <td width="25%" class="text-center">DITANGGUHKAN</td>
            <td width="25%" class="text-center">TIDAK DISETUJUI</td>
        </tr>
        <tr>
            <td class="text-center"><strong>✓</strong></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <td style="border:none;"></td>
            <td style="border:none;"></td>
            <td colspan="2" style="padding-left: 20px">

                @if ($request->workUnit && $request->nip === $request->workUnit->leader_nip)
                    {{-- JIKA PENGAJU = PIMPINAN UNIT --}}
                    Kepala Kantor Pelayanan <br>
                    Perbendaharaan Negara Tipe A2 <br>
                    Majene,
                    <br><br><br><br><br><br>

                    <span style="color: #777; font-size: 12px;">
                        Ditandatangani secara elektronik
                    </span><br>

                    Mohammad Taufiq Hidayanto <br>
                    NIP. 197603261996031004
                @else
                    {{-- JIKA PENGAJU = PEGAWAI --}}
                    Kepala {{ $request->workUnit->work_unit ?? '-' }}
                    <br><br><br><br><br><br>

                    <span style="color: #777; font-size: 12px;">
                        Ditandatangani secara elektronik
                    </span><br>

                    {{ $request->workUnit->leader_name ?? '-' }} <br>
                    NIP. {{ $request->workUnit->leader_nip ?? '-' }}
                @endif

            </td>

        </tr>
    </table>
    <br>
    <table>
        <tr>
            <td class="section-title" colspan="4">VIII. KEPUTUSAN PEJABAT YANG MEMBERIKAN CUTI (✓)</td>
        </tr>
        <tr>
            <td width="25%" class="text-center">DISETUJUI</td>
            <td width="25%" class="text-center">PERUBAHAN</td>
            <td width="25%" class="text-center">DITANGGUHKAN</td>
            <td width="25%" class="text-center">TIDAK DISETUJUI</td>
        </tr>
        <tr>
            <td class="text-center"><strong>✓</strong></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <td style="border:none;"></td>
            <td style="border:none;"></td>
            <td colspan="2" style="padding-left: 20px">Kepala Kantor Pelayanan <br>Perbendaharaan Negara Tipe A2
                Majene,
                <br><br><br><br><br><br>
                <span style="color: #777; font-size: 12px;">
                    Ditandatangani secara elektronik
                </span><br>
                Mohammad Taufiq Hidayanto <br>
                NIP. 197603261996031004
            </td>
        </tr>
    </table>
</body>

</html>
