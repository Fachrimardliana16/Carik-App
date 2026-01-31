<!DOCTYPE html>
<html>
<head>
    <title>Notulensi Rapat</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #000; padding-bottom: 10px; }
        .logo { width: 80px; height: auto; margin-bottom: 5px; }
        .title { font-size: 16px; font-weight: bold; margin-bottom: 5px; }
        .subtitle { font-size: 14px; margin-bottom: 2px; }
        .content { margin-top: 20px; }
        .info-table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        .info-table td { padding: 5px 0; vertical-align: top; }
        .info-label { width: 150px; font-weight: bold; }
        .peserta-list { margin-left: 20px; }
        .footer { margin-top: 50px; width: 100%; }
        .signature-table { width: 100%; }
        .signature-box { text-align: center; width: 50%; }
        .signature-space { height: 60px; }
    </style>
</head>
<body>
    <div class="header">
        @if($company['logo'])
            <img src="{{ $company['logo'] }}" class="logo">
        @endif
        <div class="title">{{ $company['name'] }}</div>
        <div class="subtitle">{{ $company['address'] }}</div>
        <div class="subtitle">Telp: {{ $company['phone'] }} | Email: {{ $company['email'] }}</div>
    </div>

    <div style="text-align: center;">
        <h3 style="text-decoration: underline;">NOTULENSI RAPAT</h3>
    </div>

    <div class="content">
        <table class="info-table">
            <tr>
                <td class="info-label">Hari / Tanggal</td>
                <td>: {{ $notulensi->tanggal->isoFormat('dddd, D MMMM Y') }}</td>
            </tr>
            <tr>
                <td class="info-label">Tempat</td>
                <td>: {{ $notulensi->tempat }}</td>
            </tr>
            <tr>
                <td class="info-label">Agenda / Perihal</td>
                <td>: {{ $notulensi->agenda }}</td>
            </tr>
            <tr>
                <td class="info-label">Pimpinan Rapat</td>
                <td>: {{ $notulensi->pimpinan_rapat }}</td>
            </tr>
            <tr>
                <td class="info-label">Notulis</td>
                <td>: {{ $notulensi->notulis?->name }}</td>
            </tr>
        </table>

        <h4>A. Peserta Rapat:</h4>
        <ol class="peserta-list">
            @foreach($notulensi->peserta as $p)
                <li>{{ $p['nama'] }} ({{ $p['jabatan'] ?? '-' }})</li>
            @endforeach
        </ol>

        <h4>B. Hasil Rapat / Pembahasan:</h4>
        <div class="isi">
            {!! $notulensi->isi_notulensi !!}
        </div>
    </div>

    <div class="footer">
        <table class="signature-table">
            <tr>
                <td class="signature-box">
                    Mengetahui / Menyetujui,<br>
                    <strong>{{ $notulensi->approver?->name ?? 'Pimpinan Rapat' }}</strong>
                    <div class="signature-space">
                        @if($notulensi->status === 'Approved' || $notulensi->status === 'Forwarded')
                            <div style="color: green; font-style: italic; margin-top: 20px;">Digitally Approved via SIPD</div>
                        @endif
                    </div>
                </td>
                <td class="signature-box">
                    Dicatat Oleh,<br>
                    <strong>Notulis</strong>
                    <div class="signature-space"></div>
                    ({{ $notulensi->notulis?->name }})
                </td>
            </tr>
        </table>
    </div>
</body>
</html>
