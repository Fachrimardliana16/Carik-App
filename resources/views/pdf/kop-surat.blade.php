<div style="text-align: center; border-bottom: 3px double #000; padding-bottom: 10px; margin-bottom: 20px;">
    <table width="100%">
        <tr>
            <td width="15%" style="text-align: center;">
                @if($company['logo'])
                    <img src="{{ $company['logo'] }}" style="height: 80px; width: auto;">
                @endif
            </td>
            <td width="85%" style="text-align: center;">
                <h2 style="margin: 0; padding: 0; text-transform: uppercase;">{{ $company['name'] }}</h2>
                <p style="margin: 5px 0 0 0; font-size: 12px;">
                    {{ $company['address'] }}<br>
                    Telp: {{ $company['phone'] }} | Email: {{ $company['email'] }}
                </p>
            </td>
        </tr>
    </table>
</div>
