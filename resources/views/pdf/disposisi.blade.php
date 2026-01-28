<!DOCTYPE html>
<html>
<head>
    <title>Lembar Disposisi - {{ $disposisi->suratMasuk->nomor_agenda }}</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 11pt; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th, td { border: 1px solid #000; padding: 5px; vertical-align: top; }
        .header-title { text-align: center; font-weight: bold; font-size: 14pt; margin: 20px 0; text-transform: uppercase; text-decoration: underline; }
        .no-border { border: none; }
        .label { font-weight: bold; width: 150px; }
    </style>
</head>
<body>

    @include('pdf.kop-surat')

    <div class="header-title">LEMBAR DISPOSISI</div>

    <table>
        <tr>
            <td width="50%">
                <strong>Surat Dari:</strong> {{ $disposisi->suratMasuk->pengirim }}<br>
                <strong>Nomor Surat:</strong> {{ $disposisi->suratMasuk->nomor_surat }}<br>
                <strong>Tanggal Surat:</strong> {{ $disposisi->suratMasuk->tanggal_surat->format('d/m/Y') }}
            </td>
            <td width="50%">
                <strong>Diterima Tanggal:</strong> {{ $disposisi->suratMasuk->tanggal_diterima->format('d/m/Y') }}<br>
                <strong>Nomor Agenda:</strong> {{ $disposisi->suratMasuk->nomor_agenda }}<br>
                <strong>Sifat:</strong> {{ $disposisi->suratMasuk->sifat }}
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <strong>Perihal:</strong><br>
                {{ $disposisi->suratMasuk->perihal }}
            </td>
        </tr>
    </table>

    <table>
        <tr>
            <td width="50%">
                <strong>Diteruskan Kepada Sdr:</strong><br><br>
                {{ $disposisi->kepadaUser->name }}<br>
                ({{ $disposisi->kepadaUser->username }})
            </td>
            <td width="50%">
                <strong>Dengan Hormat Harap:</strong><br><br>
                {{ $disposisi->instruksi }}
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <strong>Catatan:</strong><br>
                Status: {{ $disposisi->status }}<br>
                Batas Waktu: {{ $disposisi->batas_waktu ? $disposisi->batas_waktu->format('d/m/Y') : '-' }}
            </td>
        </tr>
    </table>

    <div style="float: right; text-align: center; width: 200px; margin-top: 20px;">
        <p>{{ \Carbon\Carbon::parse($disposisi->created_at)->format('d F Y') }}</p>
        <br><br><br>
        <p><strong>{{ $disposisi->dariUser->name }}</strong></p>
    </div>

</body>
</html>
