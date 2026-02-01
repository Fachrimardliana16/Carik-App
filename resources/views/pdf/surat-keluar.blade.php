<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Surat Keluar - {{ $surat->nomor_surat }}</title>
    <style>
        body { font-family: 'Times New Roman', Times, serif; font-size: 12pt; line-height: 1.5; }
        .content { margin-top: 20px; }
        .meta-table td { vertical-align: top; padding: 2px 0; }
        .signature { margin-top: 50px; float: right; width: 40%; text-align: center; }
        .qr-code { margin-top: 10px; }
        .qr-code svg { width: 100px; height: 100px; }
    </style>
</head>
<body>

    @include('pdf.kop-surat')

    <div style="text-align: right; margin-bottom: 20px;">
        {{ $surat->tanggal_surat->isoFormat('D MMMM Y') }}
    </div>

    <table class="meta-table" width="100%">
        <tr>
            <td width="15%">Nomor</td>
            <td width="2%">:</td>
            <td>{{ $surat->nomor_surat }}</td>
        </tr>
        <tr>
            <td>Sifat</td>
            <td>:</td>
            <td>{{ $surat->sifat }}</td>
        </tr>
        <tr>
            <td>Lampiran</td>
            <td>:</td>
            <td>-</td>
        </tr>
        <tr>
            <td>Perihal</td>
            <td>:</td>
            <td style="font-weight: bold;">{{ $surat->perihal }}</td>
        </tr>
    </table>

    <div style="margin-top: 20px;">
        Kepada Yth.<br>
        <strong>{{ $surat->tujuan }}</strong><br>
        di Tempat
    </div>

    <div class="content">
        {!! mb_convert_encoding($surat->isi_surat ?? '', 'UTF-8', 'UTF-8') !!}
    </div>

    <div class="signature">
        <p>Hormat Kami,<br>
        {{ $surat->penandatangan->name ?? 'Pejabat Berwenang' }}</p>

        @if($surat->qr_code && $surat->status == 'Selesai')
            <div class="qr-code">
                @php
                    try {
                        $validationUrl = \App\Services\QrCodeService::getValidationUrl($surat->qr_code);
                        $qrcode = \SimpleSoftwareIO\QrCode\Facades\QrCode::format('svg')
                            ->size(100)
                            ->errorCorrection('L')
                            ->generate($validationUrl);
                    } catch (\Exception $e) {
                        $qrcode = null;
                    }
                @endphp
                @if($qrcode)
                    {!! $qrcode !!}
                @endif
            </div>
            <div style="font-size: 9px; margin-top: 5px;">Dokumen ini ditandatangani secara elektronik</div>
        @else
            <br><br><br><br>
        @endif

        <p style="font-weight: bold; margin-top: 10px;">{{ $surat->penandatangan->name ?? '.........................' }}</p>
    </div>

</body>
</html>
