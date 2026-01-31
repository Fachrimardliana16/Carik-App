<div class="min-h-screen bg-gray-50 flex flex-col">
    <!-- Navigation -->
    <nav class="bg-white shadow-sm sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-20">
                <div class="flex items-center space-x-4">
                    <a href="{{ url('/') }}" class="flex items-center space-x-4">
                        @if($logoLight)
                            <img src="{{ asset('storage/' . $logoLight) }}" alt="Logo" class="h-12 w-auto">
                        @else
                            <div class="h-10 w-10 bg-primary-600 rounded-lg flex items-center justify-center text-white font-bold" style="background-color: {{ $primaryColor }}">C</div>
                        @endif
                        <div>
                            <span class="text-xl font-bold text-gray-900 tracking-tight">{{ $companyName }}</span>
                            <p class="text-xs text-gray-500 font-medium italic">Digital Correspondence</p>
                        </div>
                    </a>
                </div>
                <div class="flex items-center">
                    <a href="{{ url('/') }}" class="group text-sm font-semibold text-gray-500 hover:text-primary-600 transition-all duration-300 flex items-center" style="--hover-color: {{ $primaryColor }}">
                        <span class="mr-2 group-hover:-translate-x-1 transition-transform">&larr;</span> Kembali ke Beranda
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <main class="flex-grow py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-3xl mx-auto">
            <div class="text-center mb-12">
                <h1 class="text-3xl font-extrabold text-gray-900 sm:text-4xl">
                    Kirim <span class="text-primary-600" style="color: {{ $primaryColor }}">Surat Masuk</span>
                </h1>
                <p class="mt-4 text-lg text-gray-500">
                    Gunakan formulir ini untuk mengirimkan dokumen resmi ke bagian Sekretariat kami secara digital.
                </p>
            </div>

            @if (session()->has('success'))
                <div class="mb-8 p-6 bg-green-50 border-l-4 border-green-400 rounded-r-2xl shadow-sm animate-bounce">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-6 w-6 text-green-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-base font-bold text-green-800">
                                {{ session('success') }}
                            </p>
                            <p class="text-sm text-green-700 mt-1">
                                Anda dapat melacak progress surat menggunakan nomor surat yang anda masukkan di halaman utama.
                            </p>
                        </div>
                    </div>
                </div>
            @endif

            <div class="bg-white shadow-2xl rounded-3xl overflow-hidden border border-gray-100">
                <form wire:submit.prevent="save" class="p-8 sm:p-10 space-y-8">
                    <div class="grid grid-cols-1 gap-y-8 gap-x-6 sm:grid-cols-2">
                        <div class="sm:col-span-2">
                            <label for="nomor_surat" class="block text-sm font-bold text-gray-700 uppercase tracking-widest mb-2">Nomor Surat</label>
                            <input wire:model="nomor_surat" type="text" id="nomor_surat" placeholder="Contoh: 123/EXT/I/2024" class="block w-full px-5 py-4 text-base text-gray-900 border border-gray-300 rounded-2xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all shadow-sm">
                            @error('nomor_surat') <span class="text-xs text-red-500 font-bold mt-1 ml-2">{{ $message }}</span> @enderror
                        </div>

                        <div class="sm:col-span-2">
                            <label for="pengirim" class="block text-sm font-bold text-gray-700 uppercase tracking-widest mb-2">Instansi / Nama Pengirim</label>
                            <input wire:model="pengirim" type="text" id="pengirim" placeholder="Contoh: PT. Maju Bersama / Ahmad Salim" class="block w-full px-5 py-4 text-base text-gray-900 border border-gray-300 rounded-2xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all shadow-sm">
                            @error('pengirim') <span class="text-xs text-red-500 font-bold mt-1 ml-2">{{ $message }}</span> @enderror
                        </div>

                        <div class="sm:col-span-2">
                            <label for="perihal" class="block text-sm font-bold text-gray-700 uppercase tracking-widest mb-2">Perihal</label>
                            <input wire:model="perihal" type="text" id="perihal" placeholder="Contoh: Undangan Rapat Koordinasi" class="block w-full px-5 py-4 text-base text-gray-900 border border-gray-300 rounded-2xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all shadow-sm">
                            @error('perihal') <span class="text-xs text-red-500 font-bold mt-1 ml-2">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label for="tanggal_surat" class="block text-sm font-bold text-gray-700 uppercase tracking-widest mb-2">Tanggal Surat</label>
                            <input wire:model="tanggal_surat" type="date" id="tanggal_surat" class="block w-full px-5 py-4 text-base text-gray-900 border border-gray-300 rounded-2xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all shadow-sm">
                            @error('tanggal_surat') <span class="text-xs text-red-500 font-bold mt-1 ml-2">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label for="file" class="block text-sm font-bold text-gray-700 uppercase tracking-widest mb-2">File Dokumen (PDF/JPG)</label>
                            <input wire:model="file" type="file" id="file" class="block w-full text-sm text-gray-500 file:mr-4 file:py-4 file:px-6 file:rounded-2xl file:border-0 file:text-sm file:font-semibold file:bg-primary-50 file:text-primary-700 hover:file:bg-primary-100 transition-all cursor-pointer">
                            @error('file') <span class="text-xs text-red-500 font-bold mt-1 ml-2">{{ $message }}</span> @enderror
                            <div wire:loading wire:target="file" class="text-xs text-primary-600 font-bold mt-2 ml-2">Mengunggah file...</div>
                        </div>

                        <div class="sm:col-span-2">
                            <label for="isi_ringkas" class="block text-sm font-bold text-gray-700 uppercase tracking-widest mb-2">Isi Ringkas (Opsional)</label>
                            <textarea wire:model="isi_ringkas" id="isi_ringkas" rows="3" placeholder="Jelaskan secara singkat isi surat..." class="block w-full px-5 py-4 text-base text-gray-900 border border-gray-300 rounded-2xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all shadow-sm"></textarea>
                            @error('isi_ringkas') <span class="text-xs text-red-500 font-bold mt-1 ml-2">{{ $message }}</span> @enderror
                        </div>

                        <!-- CAPTCHA -->
                        <div class="sm:col-span-2 bg-gray-50 p-6 rounded-2xl border-2 border-dashed border-gray-200">
                            <label class="block text-sm font-bold text-gray-700 uppercase tracking-widest mb-4">Verifikasi Keamanan</label>
                            <div class="flex items-center space-x-6">
                                <div class="bg-white px-6 py-3 rounded-xl shadow-inner border border-gray-200">
                                    <span class="text-2xl font-black text-primary-600 italic tracking-widest" style="color: {{ $primaryColor }}">
                                        {{ $num1 }} + {{ $num2 }} = ?
                                    </span>
                                </div>
                                <div class="flex-grow">
                                    <input wire:model.defer="user_answer" type="number" placeholder="Jawaban" class="block w-full px-5 py-3 text-lg font-bold text-gray-900 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all shadow-sm">
                                </div>
                            </div>
                            @error('user_answer') <span class="text-xs text-red-500 font-bold mt-2 block ml-2">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="pt-6">
                        <button type="submit" wire:loading.attr="disabled" class="w-full flex justify-center py-5 px-10 border border-transparent rounded-full shadow-2xl text-lg font-black text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-4 focus:ring-offset-2 focus:ring-primary-500 transition-all duration-300 transform hover:scale-[1.02] active:scale-95 disabled:opacity-50" style="background-color: {{ $primaryColor }}">
                            <span wire:loading.remove wire:target="save">Kirim Dokumen Digital</span>
                            <span wire:loading wire:target="save">Memproses Dokumen...</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-white border-t border-gray-100 py-8">
        <div class="max-w-7xl mx-auto px-4 text-center">
            <p class="text-sm font-medium text-gray-400">
                &copy; {{ date('Y') }} {{ $companyName }}. Hak Cipta Dilindungi.
            </p>
        </div>
    </footer>
</div>
