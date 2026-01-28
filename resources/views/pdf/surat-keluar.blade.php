<!DOCTYPE html>
<html>
<head>
    <title>Surat Keluar - {{ $surat->nomor_surat }}</title>
    <style>
        body { font-family: 'Times New Roman', Times, serif; font-size: 12pt; line-height: 1.5; }
        .content { margin-top: 20px; }
        .meta-table td { vertical-align: top; padding: 2px 0; }
        .signature { margin-top: 50px; float: right; width: 40%; text-align: center; }
        .qr-code { margin-top: 10px; }
        .signature img { height: 100px; }
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
        {!! $surat->isi_surat !!}
    </div>

    <div class="signature">
        <p>Hormat Kami,<br>
        {{ $surat->penandatangan->name ?? 'Pejabat Berwenang' }}</p>

        @if($surat->qr_code && $surat->status == 'Selesai')
            <div class="qr-code">
                <?php
                    // Generate QR on the fly for PDF or use saved file. 
                    // Using simple-qrcode to generate SVG/Base64 directly is easier for dompdf?
                    // Or refer to the generated file.
                    // Let's use QrCode facade to generate base64.
                    $qrcode = \SimpleSoftwareIO\QrCode\Facades\QrCode::format('png')->size(100)->generate(\App\Services\QrCodeService::getValidationUrl($surat->qr_code));
                    $base64 = base64_encode($qrcode);
                ?>
                <img src="data:image/png;base64, {{ $base64 }}">
            </div>
            <div style="font-size: 9px; margin-top: 5px;">Dokumen ini ditandatangani secara elektronik</div>
        @else
            <br><br><br><br>
        @endif

        <p style="font-weight: bold; margin-top: 10px;">{{ $surat->penandatangan->name ?? '.........................' }}</p>
    </div>

</body>
</html>
