<div class="min-h-screen bg-gray-50 flex flex-col">
    <!-- Navigation -->
    <nav class="bg-white shadow-sm sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-20">
                <div class="flex items-center space-x-4">
                    @if($logoLight)
                        <img src="{{ asset('storage/' . $logoLight) }}" alt="Logo" class="h-12 w-auto">
                    @endif
                    <div>
                        <span class="text-xl font-bold text-gray-900 tracking-tight">{{ $companyName }}</span>
                        <p class="text-xs text-gray-500 font-medium">Digital Correspondence System</p>
                    </div>
                </div>
                <div class="flex items-center">
                    <a href="{{ route('filament.admin.auth.login') }}" class="inline-flex items-center px-6 py-2 border border-transparent text-sm font-semibold rounded-full text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 shadow-sm transition-all duration-200" style="background-color: {{ $primaryColor }}">
                        Masuk
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <main class="flex-grow">
        <div class="relative overflow-hidden bg-white">
            <div class="max-w-7xl mx-auto">
                <div class="relative z-10 pb-8 bg-white sm:pb-16 md:pb-20 lg:max-w-2xl lg:w-full lg:pb-28 xl:pb-32">
                    <svg class="hidden lg:block absolute right-0 inset-y-0 h-full w-48 text-white transform translate-x-1/2" fill="currentColor" viewBox="0 0 100 100" preserveAspectRatio="none" aria-hidden="true">
                        <polygon points="50,0 100,0 50,100 0,100" />
                    </svg>

                    <div class="pt-10 mx-auto max-w-7xl px-4 sm:pt-12 sm:px-6 md:pt-16 lg:pt-20 lg:px-8 xl:pt-28 text-center lg:text-left">
                        <h1 class="text-4xl tracking-tight font-extrabold text-gray-900 sm:text-5xl md:text-6xl">
                            <span class="block xl:inline">Cek Progress</span>
                            <span class="block text-primary-600 xl:inline" style="color: {{ $primaryColor }}">Surat Anda</span>
                        </h1>
                        <p class="mt-3 text-base text-gray-500 sm:mt-5 sm:text-lg sm:max-w-xl sm:mx-auto md:mt-5 md:text-xl lg:mx-0">
                            Layanan tracking surat digital instansi. Masukkan nomor registrasi atau nomor surat untuk melihat posisi dan status terkini dokumen Anda.
                        </p>
                        
                        <!-- Search Form -->
                        <!-- Tabbed Interface -->
                        <div class="mt-8 flex justify-center lg:justify-start">
                            <div class="bg-gray-100 p-1 rounded-full inline-flex">
                                <button wire:click="setActiveTab('tracking')" class="px-6 py-2 rounded-full text-sm font-bold transition-all duration-200 {{ $activeTab === 'tracking' ? 'bg-white text-gray-900 shadow-sm' : 'text-gray-500 hover:text-gray-700' }}">
                                    Lacak Progress
                                </button>
                                <button wire:click="setActiveTab('verification')" class="px-6 py-2 rounded-full text-sm font-bold transition-all duration-200 {{ $activeTab === 'verification' ? 'bg-white text-gray-900 shadow-sm' : 'text-gray-500 hover:text-gray-700' }}">
                                    Verifikasi QR
                                </button>
                            </div>
                        </div>

                        <!-- Form Section -->
                        <div class="mt-6">
                            @if($activeTab === 'tracking')
                                <form wire:submit.prevent="performSearch" class="max-w-xl mx-auto lg:mx-0">
                                    <div class="flex flex-col sm:flex-row gap-3">
                                        <div class="min-w-0 flex-1">
                                            <label for="search" class="sr-only">Nomor Registrasi / Surat</label>
                                            <input wire:model.defer="search" id="search" type="text" placeholder="Masukkan Nomor Registrasi / Surat" class="block w-full px-6 py-4 text-base text-gray-900 placeholder-gray-500 border border-gray-300 rounded-full focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-shadow shadow-sm">
                                        </div>
                                        <div class="sm:shrink-0">
                                            <button type="submit" class="inline-flex items-center justify-center w-full sm:w-auto py-4 px-8 rounded-full shadow-lg text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-all duration-200" style="background-color: {{ $primaryColor }}">
                                                Cari
                                            </button>
                                        </div>
                                    </div>
                                    @if (session()->has('error'))
                                        <p class="mt-3 text-sm text-red-600 font-medium ml-4">
                                            {{ session('error') }}
                                        </p>
                                    @endif
                                </form>
                            @else
                                <form wire:submit.prevent="verifyDocument" class="max-w-xl mx-auto lg:mx-0">
                                    <div class="flex flex-col sm:flex-row gap-3">
                                        <div class="min-w-0 flex-1">
                                            <label for="verificationCode" class="sr-only">Kode Validasi QR</label>
                                            <input wire:model.defer="verificationCode" id="verificationCode" type="text" placeholder="Masukkan Kode Validasi QR (UUID)" class="block w-full px-6 py-4 text-base text-gray-900 placeholder-gray-500 border border-gray-300 rounded-full focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-shadow shadow-sm">
                                        </div>
                                        <div class="sm:shrink-0">
                                            <button type="submit" class="inline-flex items-center justify-center w-full sm:w-auto py-4 px-8 rounded-full shadow-lg text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-all duration-200" style="background-color: {{ $primaryColor }}">
                                                Verifikasi
                                            </button>
                                        </div>
                                    </div>
                                    
                                    <div class="mt-4 flex justify-center sm:justify-start">
                                        <button type="button"  onclick="startQrScanner()" class="inline-flex items-center text-sm font-semibold text-gray-600 hover:text-primary-600 transition-colors">
                                            <svg class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1l6 5-6 5v1m0-12a2 2 0 012 2v6a2 2 0 01-2 2m-2-10a2 2 0 00-2 2v6a2 2 0 002 2" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                                            </svg>
                                            <span style="color: {{ $primaryColor }}">Scan QR via Kamera</span>
                                        </button>
                                    </div>

                                    <!-- QR Scanner Modal -->
                                    <div id="qr-scanner-modal" class="fixed inset-0 z-[100] hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
                                        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:p-0">
                                            <div class="fixed inset-0 bg-gray-900 bg-opacity-80 transition-opacity" aria-hidden="true" onclick="stopQrScanner()"></div>
                                            
                                            <div class="relative inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full">
                                                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                                                    <div class="sm:flex sm:items-start">
                                                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                                                            <div class="flex justify-between items-center mb-4">
                                                                <h3 class="text-xl leading-6 font-bold text-gray-900" id="modal-title">Scan QR Code</h3>
                                                                <button type="button" class="text-gray-400 hover:text-gray-500" onclick="stopQrScanner()">
                                                                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                                    </svg>
                                                                </button>
                                                            </div>
                                                            <div class="aspect-w-16 aspect-h-9 bg-black rounded-lg overflow-hidden">
                                                                <div id="reader" class="w-full h-full"></div>
                                                            </div>
                                                            <p class="mt-2 text-sm text-gray-500 animate-pulse text-center">Arahkan kamera ke QR Code dokumen...</p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                                                    <button type="button" class="w-full inline-flex justify-center rounded-full border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm" onclick="stopQrScanner()">
                                                        Batal Scan
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @if (session()->has('verification_error'))
                                        <p class="mt-3 text-sm text-red-600 font-medium ml-4">
                                            {{ session('verification_error') }}
                                        </p>
                                    @endif
                                </form>
                            @endif
                            
                            <div class="mt-8 flex flex-col sm:flex-row justify-center lg:justify-start gap-4">
                                <a href="{{ route('public.surat-masuk') }}" class="inline-flex items-center justify-center px-8 py-3 border border-gray-300 text-base font-semibold rounded-full text-gray-700 bg-white hover:bg-gray-50 transition-all duration-200 shadow-sm">
                                    <svg class="h-5 w-5 mr-2 text-primary-600" style="color: {{ $primaryColor }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                    </svg>
                                    Input Surat Masuk
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="lg:absolute lg:inset-y-0 lg:right-0 lg:w-1/2 bg-gray-100 flex items-center justify-center">
                <svg class="h-56 w-full object-cover sm:h-72 md:h-96 lg:w-full lg:h-full text-primary-100" fill="currentColor" viewBox="0 0 24 24" style="color: {{ $primaryColor }}15">
                    <path d="M13 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V9l-7-7zM13 3.5L18.5 9H13V3.5zM6 20V4h6v6h6v10H6z" />
                </svg>
            </div>
        </div>

        <!-- Results Section -->
        <div id="results" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            @if($resultMasuk)
                <div class="bg-white shadow-2xl rounded-3xl overflow-hidden border border-gray-100 transition-all duration-500 transform scale-100">
                    <div class="bg-primary-600 px-8 py-6 text-white" style="background-color: {{ $primaryColor }}">
                        <h2 class="text-2xl font-bold uppercase tracking-widest text-white">Informasi Surat Masuk</h2>
                    </div>
                    <div class="p-8">
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                            <div class="bg-gray-50 p-4 rounded-2xl">
                                <p class="text-xs font-bold text-gray-400 uppercase tracking-tighter">Nomor Surat</p>
                                <p class="text-lg font-bold text-gray-900 break-words">{{ $resultMasuk->nomor_surat }}</p>
                            </div>
                            <div class="bg-gray-50 p-4 rounded-2xl">
                                <p class="text-xs font-bold text-gray-400 uppercase tracking-tighter">Nomor Agenda</p>
                                <p class="text-lg font-bold text-gray-900">{{ $resultMasuk->nomor_agenda }}</p>
                            </div>
                            <div class="bg-gray-50 p-4 rounded-2xl">
                                <p class="text-xs font-bold text-gray-400 uppercase tracking-tighter">Perihal</p>
                                <p class="text-gray-700 font-medium">{{ $resultMasuk->perihal }}</p>
                            </div>
                            <div class="bg-gray-50 p-4 rounded-2xl">
                                <p class="text-xs font-bold text-gray-400 uppercase tracking-tighter">Status Terakhir</p>
                                <span class="inline-flex items-center px-3 py-1 mt-1 rounded-full text-sm font-semibold {{ $resultMasuk->status === 'Archived' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                    {{ $resultMasuk->status }}
                                </span>
                            </div>
                        </div>

                        @if($resultMasuk->disposisis->count() > 0)
                            <div class="mt-12">
                                <h3 class="text-xl font-bold text-gray-900 mb-8 border-l-4 border-primary-600 pl-4" style="border-color: {{ $primaryColor }}">Timeline Progress</h3>
                                <div class="flow-root">
                                    <ul role="list" class="-mb-8">
                                        @foreach($resultMasuk->disposisis->sortByDesc('created_at') as $index => $item)
                                            <li>
                                                <div class="relative pb-8">
                                                    @if(!$loop->last)
                                                        <span class="absolute top-4 left-4 -ml-px h-full w-0.5 bg-gray-200" aria-hidden="true"></span>
                                                    @endif
                                                    <div class="relative flex space-x-4">
                                                        <div>
                                                            <span class="h-8 w-8 rounded-full flex items-center justify-center ring-8 ring-white bg-primary-500 text-white" style="background-color: {{ $primaryColor }}">
                                                                <svg class="h-4 w-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                                                                </svg>
                                                            </span>
                                                        </div>
                                                        <div class="flex-1 min-w-0 flex flex-col sm:flex-row sm:justify-between items-start">
                                                            <div>
                                                                <p class="text-sm font-bold text-gray-900">Unit: {{ $item->tujuanUser?->name ?? 'Unit Tujuan' }}</p>
                                                                <p class="mt-1 text-sm text-gray-600 italic">"{{ $item->catatan ?? 'Tidak ada catatan' }}"</p>
                                                            </div>
                                                            <div class="mt-2 sm:mt-0 whitespace-nowrap text-right text-xs font-semibold text-gray-400">
                                                                <time datetime="{{ $item->created_at }}">{{ $item->created_at->format('d M Y, H:i') }}</time>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        @else
                            <div class="mt-12 p-10 bg-gray-50 rounded-3xl border-2 border-dashed border-gray-200 text-center">
                                <svg class="mx-auto h-12 w-12 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                <p class="mt-4 text-sm font-semibold text-gray-500 uppercase tracking-widest">Menunggu Disposisi</p>
                            </div>
                        @endif
                    </div>
                </div>
            @elseif($resultKeluar)
                <div class="bg-white shadow-2xl rounded-3xl overflow-hidden border border-gray-100 transition-all duration-500 transform scale-100">
                    <div class="bg-primary-600 px-8 py-6 text-white" style="background-color: {{ $primaryColor }}">
                        <h2 class="text-2xl font-bold uppercase tracking-widest text-white">Informasi Surat Keluar</h2>
                    </div>
                    <div class="p-8">
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                            <div class="bg-gray-50 p-4 rounded-2xl">
                                <p class="text-xs font-bold text-gray-400 uppercase tracking-tighter">Nomor Surat</p>
                                <p class="text-lg font-bold text-gray-900 break-words">{{ $resultKeluar->nomor_surat }}</p>
                            </div>
                            <div class="bg-gray-50 p-4 rounded-2xl">
                                <p class="text-xs font-bold text-gray-400 uppercase tracking-tighter">Perihal</p>
                                <p class="text-gray-700 font-medium">{{ $resultKeluar->perihal }}</p>
                            </div>
                            <div class="bg-gray-50 p-4 rounded-2xl">
                                <p class="text-xs font-bold text-gray-400 uppercase tracking-tighter">Status Terakhir</p>
                                <span class="inline-flex items-center px-3 py-1 mt-1 rounded-full text-sm font-semibold {{ $resultKeluar->status === 'Sent' ? 'bg-green-100 text-green-800' : 'bg-blue-100 text-blue-800' }}">
                                    {{ $resultKeluar->status }}
                                </span>
                            </div>
                            <div class="bg-gray-50 p-4 rounded-2xl">
                                <p class="text-xs font-bold text-gray-400 uppercase tracking-tighter">Tanggal Surat</p>
                                <p class="text-lg font-bold text-gray-900">{{ $resultKeluar->tanggal_surat->format('d M Y') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            @elseif($verificationResult)
                <div class="bg-white shadow-2xl rounded-3xl overflow-hidden border border-gray-100 transition-all duration-500 transform scale-100">
                    <div class="bg-green-600 px-8 py-6 text-white">
                        <h2 class="text-2xl font-bold uppercase tracking-widest text-white">Hasil Verifikasi Dokumen</h2>
                    </div>
                    <div class="p-8">
                        <div class="mb-8 flex items-center p-4 bg-green-50 rounded-2xl border border-green-200">
                            <svg class="h-10 w-10 text-green-600 mr-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <div>
                                <h3 class="text-lg font-bold text-green-800">Dokumen Valid & Asli</h3>
                                <p class="text-green-600 text-sm">Dokumen ini telah terdaftar secara resmi di sistem {{ $companyName }}.</p>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <div class="space-y-4">
                                <div class="bg-gray-50 p-4 rounded-2xl">
                                    <p class="text-xs font-bold text-gray-400 uppercase tracking-tighter">Nomor Surat</p>
                                    <p class="text-lg font-bold text-gray-900 break-words">{{ $verificationResult->nomor_surat }}</p>
                                </div>
                                <div class="bg-gray-50 p-4 rounded-2xl">
                                    <p class="text-xs font-bold text-gray-400 uppercase tracking-tighter">Perihal</p>
                                    <p class="text-gray-700 font-medium">{{ $verificationResult->perihal }}</p>
                                </div>
                            </div>
                            <div class="space-y-4">
                                <div class="bg-gray-50 p-4 rounded-2xl">
                                    <p class="text-xs font-bold text-gray-400 uppercase tracking-tighter">Penandatangan</p>
                                    <p class="text-lg font-bold text-gray-900">{{ $verificationResult->penandatangan?->name ?? 'Pejabat Berwenang' }}</p>
                                    @if($verificationResult->signed_at)
                                        <p class="text-xs text-green-600 font-bold mt-1 uppercase">✓ Ditandatangani Elektronik</p>
                                    @endif
                                </div>
                                <div class="bg-gray-50 p-4 rounded-2xl">
                                    <p class="text-xs font-bold text-gray-400 uppercase tracking-tighter">Tanggal Surat</p>
                                    <p class="text-lg font-bold text-gray-900">{{ $verificationResult->tanggal_surat->format('d M Y') }}</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mt-8 flex justify-center">
                            <a href="{{ route('val.document', ['code' => $verificationResult->qr_code]) }}" target="_blank" class="inline-flex items-center text-primary-600 font-bold hover:underline" style="color: {{ $primaryColor }}">
                                Lihat Halaman Validasi Resmi
                                <svg class="ml-2 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>
            @endif
        </div>
        <!-- Features Section -->
        <div class="bg-gray-50 py-24 sm:py-32">
            <div class="mx-auto max-w-7xl px-6 lg:px-8">
                <div class="mx-auto max-w-2xl lg:text-center">
                    <h2 class="text-base font-semibold leading-7 text-primary-600" style="color: {{ $primaryColor }}">Persuratan Digital</h2>
                    <p class="mt-2 text-3xl font-bold tracking-tight text-gray-900 sm:text-4xl">Solusi Modern Administrasi Anda</p>
                    <p class="mt-6 text-lg leading-8 text-gray-600">
                        Sistem Informasi Pengelolaan Dokumen (SIPD) memberikan kemudahan dalam mengelola, melacak, dan mengarsipkan surat secara digital.
                    </p>
                </div>
                <div class="mx-auto mt-16 max-w-2xl sm:mt-20 lg:mt-24 lg:max-w-4xl">
                    <dl class="grid max-w-xl grid-cols-1 gap-x-8 gap-y-10 lg:max-w-none lg:grid-cols-2 lg:gap-y-16">
                        <div class="relative pl-16">
                            <dt class="text-base font-semibold leading-7 text-gray-900">
                                <div class="absolute left-0 top-0 flex h-10 w-10 items-center justify-center rounded-lg bg-primary-600" style="background-color: {{ $primaryColor }}">
                                    <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 16.5V9.75m0 0l3 3m-3-3l-3 3M6.75 19.5a4.5 4.5 0 01-1.41-8.775 5.25 5.25 0 0110.233-2.33 3 3 0 013.758 3.848A3.752 3.752 0 0118 19.5H6.75z" />
                                    </svg>
                                </div>
                                Arsip Digital Aman
                            </dt>
                            <dd class="mt-2 text-base leading-7 text-gray-600">Semua dokumen disimpan dengan enkripsi standar industri untuk menjamin keamanan data instansi Anda.</dd>
                        </div>
                        <div class="relative pl-16">
                            <dt class="text-base font-semibold leading-7 text-gray-900">
                                <div class="absolute left-0 top-0 flex h-10 w-10 items-center justify-center rounded-lg bg-primary-600" style="background-color: {{ $primaryColor }}">
                                    <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                </div>
                                Tracking Real-time
                            </dt>
                            <dd class="mt-2 text-base leading-7 text-gray-600">Pantau posisi surat anda secara real-time. Ketahui tepat di meja mana dokumen anda sedang diproses.</dd>
                        </div>
                        <div class="relative pl-16">
                            <dt class="text-base font-semibold leading-7 text-gray-900">
                                <div class="absolute left-0 top-0 flex h-10 w-10 items-center justify-center rounded-lg bg-primary-600" style="background-color: {{ $primaryColor }}">
                                    <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M7.5 8.25h9m-9 3h9m-9 3h3m-6.75 3h12a3 3 0 003-3V6.75a3 3 0 00-3-3H3.75a3 3 0 00-3-3v10.5a3 3 0 003 3z" />
                                    </svg>
                                </div>
                                Notifikasi Cepat
                            </dt>
                            <dd class="mt-2 text-base leading-7 text-gray-600">Sistem memberikan notifikasi instan kepada petugas saat ada surat baru atau disposisi yang memerlukan tindakan.</dd>
                        </div>
                        <div class="relative pl-16">
                            <dt class="text-base font-semibold leading-7 text-gray-900">
                                <div class="absolute left-0 top-0 flex h-10 w-10 items-center justify-center rounded-lg bg-primary-600" style="background-color: {{ $primaryColor }}">
                                    <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                Tanda Tangan Digital
                            </dt>
                            <dd class="mt-2 text-base leading-7 text-gray-600">Mendukung verifikasi keaslian dokumen menggunakan teknologi QR Code dan Tanda Tangan Elektronik yang sah.</dd>
                        </div>
                    </dl>
                </div>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-white border-t border-gray-100">
        <div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <p class="text-sm font-bold text-gray-400 uppercase tracking-[0.2em] mb-4">Powered By</p>
                <div class="flex justify-center space-x-6 text-gray-400 font-bold italic tracking-tighter text-2xl">
                    <span class="text-gray-300">CARIK</span>
                    <span class="text-primary-600" style="color: {{ $primaryColor }}">APP</span>
                </div>
                <p class="mt-8 text-center text-sm font-medium text-gray-400">
                    &copy; {{ date('Y') }} {{ $companyName }}. Hak Cipta Dilindungi Undang-Undang.
                </p>
            </div>
        </div>
    </footer>
</div>

<script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
<script>
    let html5QrcodeScanner = null;

    function startQrScanner() {
        document.getElementById('qr-scanner-modal').classList.remove('hidden');
        
        html5QrcodeScanner = new Html5Qrcode("reader");
        const config = { fps: 10, qrbox: { width: 300, height: 300 } };
        
        html5QrcodeScanner.start({ facingMode: "environment" }, config, onScanSuccess);
    }

    function stopQrScanner() {
        if (html5QrcodeScanner) {
            html5QrcodeScanner.stop().then((ignore) => {
                // QR Code scanning is stopped.
                html5QrcodeScanner.clear();
                document.getElementById('qr-scanner-modal').classList.add('hidden');
            }).catch((err) => {
                // Stop failed.
                console.error(err);
                document.getElementById('qr-scanner-modal').classList.add('hidden');
            });
        } else {
            document.getElementById('qr-scanner-modal').classList.add('hidden');
        }
    }

    function onScanSuccess(decodedText, decodedResult) {
        // Handle the scanned code as you like, for example:
        // console.log(`Code matched = ${decodedText}`, decodedResult);
        
        // Stop scanning
        stopQrScanner();
        
        // Set the value to the input field
        const input = document.getElementById('verificationCode');
        input.value = decodedText;
        
        // Dispatch input event to update Livewire model
        input.dispatchEvent(new Event('input'));
        
        // Optionally trigger verification immediately
        @this.set('verificationCode', decodedText);
        @this.call('verifyDocument');
    }
</script>
