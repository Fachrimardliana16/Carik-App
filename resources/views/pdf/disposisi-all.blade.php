<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Lembar Disposisi</title>
    <style>
        @page { margin: 20mm; }
        body {
            font-family: Arial, sans-serif;
            font-size: 11pt;
            line-height: 1.4;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 3px solid #000;
            padding-bottom: 10px;
        }
        .header h2 {
            margin: 5px 0;
            font-size: 16pt;
        }
        .header p {
            margin: 3px 0;
            font-size: 10pt;
        }
        .content {
            margin-top: 20px;
        }
        .section {
            margin-bottom: 15px;
        }
        .section-title {
            font-weight: bold;
            background-color: #f0f0f0;
            padding: 5px;
            margin-bottom: 5px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table td {
            padding: 5px;
            vertical-align: top;
        }
        table td:first-child {
            width: 30%;
            font-weight: bold;
        }
        .disposisi-box {
            border: 1px solid #000;
            padding: 10px;
            margin-top: 10px;
            min-height: 80px;
            page-break-inside: avoid;
        }
        .footer {
            margin-top: 30px;
            text-align: right;
        }
    </style>
</head>
<body>
    <div class="header">
        @php
            $settings = \App\Models\CompanySetting::first();
        @endphp
        @if($settings && $settings->logo)
            <img src="{{ public_path('storage/' . $settings->logo) }}" alt="Logo" style="height: 50px;">
        @endif
        <h2>{{ $settings->company_name ?? 'LEMBAR DISPOSISI' }}</h2>
        <p>{{ $settings->address ?? '' }}</p>
    </div>

    <div class="content">
        <div class="section">
            <div class="section-title">Informasi Surat</div>
            <table>
                <tr>
                    <td>Nomor Surat</td>
                    <td>: {{ $suratMasuk->nomor_surat }}</td>
                </tr>
                <tr>
                    <td>Nomor Registrasi</td>
                    <td>: {{ $suratMasuk->nomor_agenda }}</td>
                </tr>
                <tr>
                    <td>Tanggal Surat</td>
                    <td>: {{ $suratMasuk->tanggal_surat->format('d F Y') }}</td>
                </tr>
                <tr>
                    <td>Pengirim</td>
                    <td>: {{ $suratMasuk->pengirim }}</td>
                </tr>
                <tr>
                    <td>Perihal</td>
                    <td>: {{ $suratMasuk->perihal }}</td>
                </tr>
                <tr>
                    <td>Sifat</td>
                    <td>: {{ $suratMasuk->sifat }}</td>
                </tr>
            </table>
        </div>

        <div class="section">
            <div class="section-title">Daftar Disposisi</div>
            @foreach($suratMasuk->disposisis->sortBy('created_at') as $index => $dispo)
            <div class="disposisi-box">
                <table>
                    <tr>
                        <td colspan="2" style="font-weight: bold;">Disposisi #{{ $index + 1 }}</td>
                    </tr>
                    <tr>
                        <td>Dari</td>
                        <td>: {{ $dispo->dariUser->name }}</td>
                    </tr>
                    <tr>
                        <td>Kepada</td>
                        <td>: {{ $dispo->kepadaUser->name }}</td>
                    </tr>
                    <tr>
                        <td>Tanggal</td>
                        <td>: {{ $dispo->created_at->format('d F Y, H:i') }}</td>
                    </tr>
                    <tr>
                        <td>Prioritas</td>
                        <td>: {{ $dispo->prioritas }}</td>
                    </tr>
                    @if($dispo->batas_waktu)
                    <tr>
                        <td>Batas Waktu</td>
                        <td>: {{ $dispo->batas_waktu->format('d F Y') }}</td>
                    </tr>
                    @endif
                    <tr>
                        <td>Instruksi</td>
                        <td>: {!! nl2br(e($dispo->instruksi)) !!}</td>
                    </tr>
                    <tr>
                        <td>Status</td>
                        <td>: <strong>{{ $dispo->status }}</strong></td>
                    </tr>
                </table>
            </div>
            @endforeach
        </div>
    </div>

    <div class="footer">
        <p>Dicetak pada: {{ now()->format('d F Y, H:i') }}</p>
    </div>
</body>
</html>
