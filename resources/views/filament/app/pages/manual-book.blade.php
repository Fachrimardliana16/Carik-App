<x-filament-panels::page>
    <div class="space-y-4">
        @forelse($manualBooks as $book)
            <div x-data="{ open: false }" class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-white/10 shadow-sm overflow-hidden ring-1 ring-gray-950/5 dark:ring-white/10">
                <button @click="open = !open" class="w-full px-6 py-4 text-left flex justify-between items-center bg-gray-50 dark:bg-white/5 hover:bg-gray-100 dark:hover:bg-white/10 transition-colors duration-200 focus:outline-none">
                    <span class="text-lg font-bold text-gray-950 dark:text-white">{{ $book->title }}</span>
                    <span :class="{'transform rotate-180': open}" class="transition-transform duration-200 text-gray-400 dark:text-gray-500">
                        <x-heroicon-o-chevron-down class="w-5 h-5" />
                    </span>
                </button>
                <div x-show="open" 
                     x-collapse
                     class="px-6 py-6 prose dark:prose-invert max-w-none text-gray-600 dark:text-gray-300 border-t border-gray-200 dark:border-white/10">
                    {!! $book->content !!}
                </div>
            </div>
        @empty
            <div class="text-center py-12 bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-white/10 shadow-sm ring-1 ring-gray-950/5 dark:ring-white/10">
                <div class="flex flex-col items-center justify-center text-gray-400 dark:text-gray-500">
                    <x-heroicon-o-book-open class="w-12 h-12 mb-4" />
                    <p class="text-lg font-medium">Belum ada panduan yang tersedia.</p>
                </div>
            </div>
        @endforelse
    </div>
</x-filament-panels::page>
