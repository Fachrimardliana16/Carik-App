<div class="px-4 py-4">
    <h3 class="text-lg font-medium text-gray-900 mb-4">Tracking Disposisi</h3>
    <ol class="relative border-l border-gray-200 dark:border-gray-700">                  
        {{-- Status Surat Masuk Awal --}}
        <li class="mb-10 ml-6">            
            <span class="absolute flex items-center justify-center w-6 h-6 bg-blue-100 rounded-full -left-3 ring-8 ring-white dark:ring-gray-900 dark:bg-blue-900">
                <svg class="w-3 h-3 text-blue-800 dark:text-blue-300" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"></path></svg>
            </span>
            <h3 class="flex items-center mb-1 text-lg font-semibold text-gray-900 dark:text-white">
                Surat Diterima / Dicatat
                <span class="bg-blue-100 text-blue-800 text-sm font-medium mr-2 px-2.5 py-0.5 rounded dark:bg-blue-900 dark:text-blue-300 ml-3">Start</span>
            </h3>
            <time class="block mb-2 text-sm font-normal leading-none text-gray-400 dark:text-gray-500">
                {{ $getRecord()->created_at->format('d F Y, H:i') }}
            </time>
            <p class="mb-4 text-base font-normal text-gray-500 dark:text-gray-400">
                Diterima oleh: {{ $getRecord()->creator->name ?? 'System' }}<br>
                Status Awal: {{ $getRecord()->status }}
            </p>
        </li>
        
        {{-- Loop Disposisi --}}
        @foreach($getRecord()->disposisis->sortBy('created_at') as $dispo)
        <li class="mb-10 ml-6">
            <span class="absolute flex items-center justify-center w-6 h-6 bg-yellow-100 rounded-full -left-3 ring-8 ring-white dark:ring-gray-900 dark:bg-yellow-900">
                 <svg class="w-3 h-3 text-yellow-800 dark:text-yellow-300" fill="currentColor" viewBox="0 0 20 20"><path d="M10 2a1 1 0 00-1 1v1a1 1 0 002 0V3a1 1 0 00-1-1zM4 4h3a3 3 0 006 0h3a2 2 0 012 2v9a2 2 0 01-2 2H4a2 2 0 01-2-2V6a2 2 0 012-2zm2.5 7a1.5 1.5 0 100-3 1.5 1.5 0 000 3zm2.45 4a2.5 2.5 0 10-4.9 0h4.9zM12 9a1 1 0 100 2h3a1 1 0 100-2h-3zm-1 4a1 1 0 011-1h2a1 1 0 110 2h-2a1 1 0 01-1-1z" /></svg>
            </span>
            <h3 class="mb-1 text-lg font-semibold text-gray-900 dark:text-white">
                Disposisi ke: {{ $dispo->kepadaUser->name }}
            </h3>
            <time class="block mb-2 text-sm font-normal leading-none text-gray-400 dark:text-gray-500">
                {{ $dispo->created_at->format('d F Y, H:i') }}
            </time>
            <div class="p-4 bg-white border border-gray-200 rounded-lg shadow-sm dark:bg-gray-700 dark:border-gray-600">
                <div class="justify-between items-center mb-3 sm:flex">
                    <time class="mb-1 text-xs font-normal text-gray-400 sm:order-last sm:mb-0">
                        Batas Waktu: {{ $dispo->batas_waktu ? $dispo->batas_waktu->format('d M Y') : '-' }}
                    </time>
                    <div class="text-sm font-normal text-gray-500 lex dark:text-gray-300">Dari: {{ $dispo->dariUser->name }}</div>
                </div>
                <div class="p-3 text-xs italic font-normal text-gray-500 border border-gray-200 rounded-lg bg-gray-50 dark:bg-gray-600 dark:border-gray-500 dark:text-gray-300">
                    "{!! $dispo->instruksi !!}"
                </div>
                
                @if($dispo->status == 'Selesai')
                <div class="mt-3 flex items-center text-green-600 text-sm font-bold">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    Selesai: {{ $dispo->catatan_penyelesaian ?? 'Tanpa catatan' }}
                </div>
                @else
                <div class="mt-3 text-sm text-yellow-600">
                    Status: {{ $dispo->status }}
                </div>
                @endif
            </div>
        </li>
        @endforeach
    </ol>
</div>
