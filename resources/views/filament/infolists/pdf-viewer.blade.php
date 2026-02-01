@php
    $state = $getState();
    $url = $state ? route('file.download', ['path' => $state]) : null;
@endphp

@if($url)
    <div class="w-full h-[600px] border rounded-lg overflow-hidden">
        <iframe src="{{ $url }}" class="w-full h-full" frameborder="0"></iframe>
    </div>
@else
    <div class="text-gray-500 italic">Tidak ada file lampiran atau format tidak didukung untuk preview.</div>
@endif
