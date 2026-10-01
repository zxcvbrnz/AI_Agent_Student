<div class="p-6 max-w-4xl mx-auto space-y-6">

    <!-- Header Section -->
    <div class="flex items-center justify-between border-b border-slate-200 pb-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Core Prompt System</h1>
            <p class="text-sm text-slate-500">Atur instruksi dasar (system prompt) utama untuk AI.</p>
        </div>
    </div>

    <!-- Alert Flash Message -->
    @if (session()->has('message'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)"
            class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl text-sm flex justify-between items-center">
            <span>{{ session('message') }}</span>
            <button @click="show = false" class="text-emerald-500 hover:text-emerald-700 font-bold">&times;</button>
        </div>
    @endif

    <!-- Form Single Data -->
    <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm">
        <form wire:submit.prevent="save" class="space-y-4">
            <div>
                <label class="block text-xs font-semibold text-slate-600 uppercase tracking-wider mb-2">
                    Isi System Prompt
                </label>
                <textarea wire:model="promt" rows="16"
                    placeholder="Tuliskan instruksi utama AI di sini..."
                    class="w-full p-4 bg-slate-50 border border-slate-200 rounded-xl text-sm font-mono leading-relaxed focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 resize-y @error('promt') border-red-500 @enderror"></textarea>
                
                @error('promt')
                    <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span>
                @enderror
            </div>

            <div class="flex items-center justify-between pt-2 border-t border-slate-100">
                <span class="text-xs text-slate-400">
                    Sistem akan selalu memperbarui record tunggal di database.
                </span>

                <button type="submit" wire:loading.attr="disabled"
                    class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 disabled:bg-slate-300 text-white font-medium text-sm rounded-xl transition shadow-sm flex items-center gap-2">
                    <span wire:loading.remove wire:target="save">Simpan Prompt</span>
                    <span wire:loading wire:target="save" class="flex items-center gap-2">
                        <svg class="animate-spin h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Menyimpan...
                    </span>
                </button>
            </div>
        </form>
    </div>

</div>