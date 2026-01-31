<!DOCTYPE html>
<html>
<head>
    <title>S-Planer Report</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; color: #333; }
        .header { text-align: center; border-bottom: 2px solid #000; padding-bottom: 10px; margin-bottom: 20px; }
        .header img { height: 60px; }
        .header h1 { margin: 5px 0; font-size: 18px; text-transform: uppercase; }
        .header p { margin: 0; font-size: 11px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #ccc; padding: 8px; text-align: left; }
        th { bg-color: #f2f2f2; font-weight: bold; }
        .status { font-weight: bold; text-transform: uppercase; font-size: 10px; }
        .footer { position: fixed; bottom: 0; width: 100%; text-align: right; font-size: 10px; border-top: 1px solid #eee; padding-top: 5px; }
    </style>
</head>
<body>
    <div class="header">
        @if($company['logo'])
            <img src="{{ $company['logo'] }}">
        @endif
        <h1>Laporan Agenda Kegiatan (S-Planer)</h1>
        <p>{{ $company['name'] }}</p>
        <p>{{ $company['address'] }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Tanggal & Waktu</th>
                <th>Kegiatan</th>
                <th>Lokasi</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($agendas as $index => $agenda)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>
                        {{ $agenda->start_time->format('d M Y') }}<br>
                        <small>{{ $agenda->start_time->format('H:i') }} - {{ $agenda->end_time->format('H:i') }}</small>
                    </td>
                    <td><strong>{{ $agenda->title }}</strong></td>
                    <td>{{ $agenda->location ?: '-' }}</td>
                    <td class="status">{{ $agenda->status }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        Dicetak pada: {{ date('d M Y H:i:s') }}
    </div>
</body>
</html>
