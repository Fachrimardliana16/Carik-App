<div class="p-6 bg-white border rounded-xl shadow-sm min-h-[400px]">
    <div class="mb-4 border-b pb-4 flex items-center justify-between">
        @php
            $logo = $get('logo_surat');
            if (is_array($logo)) {
                $logo = array_values($logo)[0] ?? null;
            }
            $kop = $get('kop_surat') ?: \App\Services\SettingsService::getCompanyName();
        @endphp
        
        <div class="flex items-center space-x-4">
            @if($logo)
                <img src="{{ asset('storage/' . $logo) }}" class="h-16 w-auto">
            @endif
            <div class="text-xl font-bold uppercase tracking-tight">
                {!! $kop !!}
            </div>
        </div>
        <div class="text-right text-gray-500 text-xs">
            <p>PREVIEW MODE</p>
            <p>{{ date('d M Y') }}</p>
        </div>
    </div>
    
    <div class="prose max-w-none">
        {!! $getState() !!}
    </div>
    
    <div class="mt-8 border-t pt-4 text-center text-xs text-gray-400">
        <p>Dokumen ini adalah preview sistem SIPD Digital.</p>
    </div>
</div>
