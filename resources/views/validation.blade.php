<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Validasi Dokumen Digital - SIPD</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-gray-50 min-h-screen flex items-center justify-center p-4">
    <div class="bg-white max-w-lg w-full rounded-2xl shadow-xl overflow-hidden border border-gray-100">
        <!-- Header -->
        <div class="bg-blue-600 p-8 text-center relative overflow-hidden">
            <div class="absolute top-0 left-0 w-full h-full bg-gradient-to-br from-blue-500 to-blue-700 opacity-90"></div>
            <div class="relative z-10">
                <div class="bg-white w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4 shadow-lg">
                    @if(\App\Services\SettingsService::getLogoLight())
                        <img src="{{ \App\Services\SettingsService::getLogoLight() }}" alt="Logo" class="w-10 h-10 object-contain">
                    @else
                        <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    @endif
                </div>
                <h1 class="text-white text-2xl font-bold mb-1">{{ \App\Services\SettingsService::getCompanyName() }}</h1>
                <p class="text-blue-100 text-sm">Validasi Dokumen Resmi</p>
            </div>
        </div>

        <!-- Status -->
        <div class="p-8 text-center border-b border-gray-100">
            @if($isValid)
                <div class="inline-flex items-center px-4 py-2 rounded-full bg-green-50 text-green-700 mb-4 border border-green-200">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    <span class="font-semibold">Dokumen Valid & Asli</span>
                </div>
                <p class="text-gray-500 text-sm">Dokumen ini telah terdaftar secara resmi di sistem kami.</p>
            @else
                <div class="inline-flex items-center px-4 py-2 rounded-full bg-yellow-50 text-yellow-700 mb-4 border border-yellow-200">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                    </svg>
                    <span class="font-semibold">Status Dokumen: {{ $surat->status }}</span>
                </div>
                <p class="text-gray-500 text-sm">Dokumen ini belum berstatus final atau masih dalam proses.</p>
            @endif
        </div>

        <!-- Detail Information -->
        <div class="p-8 space-y-6">
            <div class="flex justify-between items-start">
                <div class="text-gray-500 text-sm">Nomor Surat</div>
                <div class="text-gray-900 font-semibold text-right">{{ $surat->nomor_surat }}</div>
            </div>
            
            <div class="flex justify-between items-start">
                <div class="text-gray-500 text-sm">Tanggal Surat</div>
                <div class="text-gray-900 font-semibold text-right">{{ $surat->tanggal_surat->format('d F Y') }}</div>
            </div>

            <div class="flex justify-between items-start">
                <div class="text-gray-500 text-sm">Perihal</div>
                <div class="text-gray-900 font-semibold text-right max-w-[60%]">{{ $surat->perihal }}</div>
            </div>

            <div class="flex justify-between items-start">
                <div class="text-gray-500 text-sm">Tujuan</div>
                <div class="text-gray-900 font-semibold text-right max-w-[60%]">{{ $surat->tujuan }}</div>
            </div>

            <div class="flex justify-between items-start">
                <div class="text-gray-500 text-sm">Penandatangan</div>
                <div class="text-gray-900 font-semibold text-right">
                    {{ $surat->penandatangan->name ?? '-' }}
                    @if($surat->signed_at)
                    <div class="text-xs text-green-600 mt-1 flex items-center justify-end">
                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Ditandatangani secara elektronik
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="bg-gray-50 p-6 text-center text-xs text-gray-400 border-t border-gray-100">
            &copy; {{ date('Y') }} {{ \App\Services\SettingsService::getCompanyName() }}. All rights reserved.<br>
            Dicetak melalui Carik-App Digital
        </div>
    </div>
</body>
</html>
