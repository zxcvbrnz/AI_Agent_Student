<div class="space-y-6">
    <!-- Header Page & Flash Message -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Kelola Subject & AI Prompt</h1>
            <p class="text-sm text-slate-500">Tambah, ubah, dan atur instruksi AI untuk setiap mata pelajaran.</p>
        </div>
        <button wire:click="create"
            class="px-4 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-sm font-medium transition flex items-center justify-center gap-2 shadow-sm shadow-indigo-600/30">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            <span>Tambah Subject</span>
        </button>
    </div>

    @if (session()->has('message'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)"
            class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-700 text-sm rounded-xl flex items-center justify-between">
            <div class="flex items-center gap-2">
                <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
                <span>{{ session('message') }}</span>
            </div>
            <button @click="show = false" class="text-emerald-500 hover:text-emerald-700">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
    @endif

    <!-- Card & Table Data -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <div
            class="p-4 border-b border-slate-200 flex flex-col sm:flex-row sm:items-center justify-between gap-3 bg-slate-50/50">
            <div class="relative w-full sm:w-72">
                <input wire:model.live.debounce.300ms="search" type="text" placeholder="Cari nama subject..."
                    class="w-full bg-white border border-slate-200 text-slate-800 text-sm rounded-xl pl-9 pr-4 py-2 focus:outline-none focus:border-indigo-500 transition">
                <svg class="w-4 h-4 text-slate-400 absolute left-3 top-3" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr
                        class="bg-slate-50 border-b border-slate-200 text-xs font-semibold uppercase tracking-wider text-slate-500">
                        <th class="py-3 px-4">Subject</th>
                        <th class="py-3 px-4">System Prompt AI</th>
                        <th class="py-3 px-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-sm text-slate-700">
                    @forelse ($subjects as $subject)
                        <tr class="hover:bg-slate-50/60 transition">
                            <td class="py-3 px-4 whitespace-nowrap">
                                <div class="flex items-center gap-3">
                                    <span
                                        class="text-2xl p-2 bg-slate-100 rounded-xl leading-none">{{ $subject->icon }}</span>
                                    <span class="font-semibold text-slate-900">{{ $subject->name }}</span>
                                </div>
                            </td>
                            <td class="py-3 px-4 max-w-md">
                                <p
                                    class="text-slate-600 line-clamp-2 text-xs font-mono bg-slate-50 p-2 rounded-lg border border-slate-200/60">
                                    {{ $subject->system_prompt }}
                                </p>
                            </td>
                            <td class="py-3 px-4 whitespace-nowrap text-center">
                                <div class="flex items-center justify-center gap-2">
                                    <button wire:click="edit({{ $subject->id }})"
                                        class="p-2 text-slate-500 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition"
                                        title="Edit">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </button>
                                    <button wire:click="confirmDelete({{ $subject->id }})"
                                        class="p-2 text-slate-500 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition"
                                        title="Hapus">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="py-8 text-center text-slate-400">
                                <span class="text-4xl block mb-2">📚</span>
                                Tidak ada data subject ditemukan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($subjects->hasPages())
            <div class="p-4 border-t border-slate-200">
                {{ $subjects->links() }}
            </div>
        @endif
    </div>

    <!-- Modal Form (Create / Edit) -->
    @if ($isModalOpen)
        <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm transition-opacity" wire:click="closeModal">
                </div>

                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                <div
                    class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-xl sm:w-full border border-slate-200">
                    <div class="bg-white p-6">
                        <div class="flex items-center justify-between pb-4 border-b border-slate-100">
                            <h3 class="text-lg font-bold text-slate-900">
                                {{ $subjectId ? 'Edit Subject' : 'Tambah Subject Baru' }}
                            </h3>
                            <button wire:click="closeModal" class="text-slate-400 hover:text-slate-600">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>

                        <form wire:submit.prevent="store" class="space-y-4 mt-4">
                            <div>
                                <label class="block text-xs font-semibold text-slate-600 uppercase mb-1">Icon
                                    (Emoji)</label>
                                <input type="text" wire:model="icon" placeholder="Misal: 📐, 🧪, 📖"
                                    class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-indigo-500 focus:bg-white transition">
                                @error('icon')
                                    <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-slate-600 uppercase mb-1">Nama
                                    Subject</label>
                                <input type="text" wire:model="name" placeholder="Misal: Matematika Dasar"
                                    class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-indigo-500 focus:bg-white transition">
                                @error('name')
                                    <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-slate-600 uppercase mb-1">System Prompt
                                    AI</label>
                                <textarea wire:model="system_prompt" rows="5"
                                    placeholder="Tuliskan instruksi peran AI untuk mata pelajaran ini..."
                                    class="w-full bg-slate-50 border border-slate-200 rounded-xl p-3 text-xs font-mono focus:outline-none focus:border-indigo-500 focus:bg-white transition"></textarea>
                                @error('system_prompt')
                                    <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- File Referensi / Lampiran Subject -->
                            <div>
                                <label class="block text-xs font-semibold text-slate-600 uppercase mb-2">
                                    File Referensi / Lampiran Subject <span class="normal-case text-slate-400 font-normal">(Opsional)</span>
                                </label>

                                <!-- Zone Drop / Upload File -->
                                <div class="relative border-2 border-dashed border-slate-200 hover:border-indigo-400 bg-slate-50/50 hover:bg-indigo-50/30 rounded-xl p-4 transition group text-center cursor-pointer">
                                    <input type="file" wire:model="files" multiple class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">
                                    <div class="flex flex-col items-center justify-center gap-1">
                                        <div class="p-2 bg-white rounded-full shadow-sm text-indigo-600 border border-slate-100 group-hover:scale-110 transition-transform">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                                            </svg>
                                        </div>
                                        <p class="text-xs font-medium text-slate-700 mt-1">
                                            <span class="text-indigo-600 font-semibold">Klik untuk memilih</span> atau tarik file ke sini
                                        </p>
                                        <p class="text-[10px] text-slate-400">PDF, DOCX, TXT, atau gambar</p>
                                    </div>
                                </div>

                                <!-- Indicator Loading -->
                                <div wire:loading wire:target="files" class="flex items-center gap-2 text-xs text-indigo-600 mt-2 font-medium">
                                    <svg class="animate-spin w-3.5 h-3.5 text-indigo-600" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                    <span>Mengunggah file...</span>
                                </div>

                                @error('files.*')
                                    <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span>
                                @enderror

                                <!-- Card Grid Kotak File Tersimpan -->
                                @if (!empty($existingFiles))
                                    <div class="mt-3 space-y-1.5">
                                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">File Tersimpan</span>
                                        <div class="grid grid-cols-2 sm:grid-cols-3 gap-2.5">
                                            @foreach ($existingFiles as $index => $file)
                                                @php
                                                    $ext = strtoupper(pathinfo($file['name'], PATHINFO_EXTENSION) ?: ($file['type'] ?? 'FILE'));
                                                    $size = isset($file['size']) 
                                                        ? ($file['size'] >= 1048576 
                                                            ? number_format($file['size'] / 1048576, 1) . ' MB' 
                                                            : number_format($file['size'] / 1024, 0) . ' KB') 
                                                        : null;
                                                @endphp
                                                <div class="relative group bg-slate-50 border border-slate-200/80 rounded-xl p-3 flex flex-col justify-between hover:border-slate-300 hover:shadow-sm transition">
                                                    <!-- Header Card: Badge Tipe & Button Delete -->
                                                    <div class="flex items-center justify-between mb-2">
                                                        <span class="text-[9px] font-bold px-1.5 py-0.5 rounded bg-slate-200 text-slate-600 uppercase tracking-wider">
                                                            {{ $ext }}
                                                        </span>
                                                        <button type="button" 
                                                            wire:click="removeExistingFile({{ $index }})"
                                                            class="text-slate-400 hover:text-rose-600 hover:bg-rose-50 p-1 rounded-md transition"
                                                            title="Hapus File">
                                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                            </svg>
                                                        </button>
                                                    </div>

                                                    <!-- Center Card: Icon & Nama File -->
                                                    <div class="flex flex-col items-center text-center my-1">
                                                        <div class="p-2 bg-white rounded-lg text-slate-500 border border-slate-100 shadow-2xs mb-1.5">
                                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                            </svg>
                                                        </div>
                                                        <p class="text-xs font-semibold text-slate-700 truncate w-full" title="{{ $file['name'] }}">
                                                            {{ $file['name'] }}
                                                        </p>
                                                    </div>

                                                    <!-- Footer Card: Informasi Ukuran File -->
                                                    <div class="mt-2 pt-2 border-t border-slate-200/60 flex items-center justify-between text-[10px] text-slate-400">
                                                        <span>Ukuran:</span>
                                                        <span class="font-medium text-slate-500">{{ $size ?? '-' }}</span>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif

                                <!-- Card Grid File Baru (Diupload) -->
                                @if (!empty($files))
                                    <div class="mt-3 space-y-1.5">
                                        <span class="text-[10px] font-bold text-indigo-400 uppercase tracking-wider">File Baru Di-Upload</span>
                                        <div class="grid grid-cols-2 sm:grid-cols-3 gap-2.5">
                                            @foreach ($files as $index => $file)
                                                @php
                                                    $ext = strtoupper($file->getClientOriginalExtension() ?: 'FILE');
                                                    $fileSize = $file->getSize();
                                                    $sizeStr = $fileSize >= 1048576 
                                                        ? number_format($fileSize / 1048576, 1) . ' MB' 
                                                        : number_format($fileSize / 1024, 0) . ' KB';
                                                @endphp
                                                <div class="relative group bg-indigo-50/50 border border-indigo-200/70 rounded-xl p-3 flex flex-col justify-between hover:border-indigo-300 transition">
                                                    <!-- Header Card: Badge Tipe & Button Delete -->
                                                    <div class="flex items-center justify-between mb-2">
                                                        <span class="text-[9px] font-bold px-1.5 py-0.5 rounded bg-indigo-100 text-indigo-700 uppercase tracking-wider">
                                                            {{ $ext }}
                                                        </span>
                                                        <button type="button" 
                                                            wire:click="removeNewFile({{ $index }})"
                                                            class="text-indigo-400 hover:text-rose-600 hover:bg-rose-50 p-1 rounded-md transition"
                                                            title="Batal Upload">
                                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                            </svg>
                                                        </button>
                                                    </div>

                                                    <!-- Center Card: Icon & Nama File -->
                                                    <div class="flex flex-col items-center text-center my-1">
                                                        <div class="p-2 bg-white rounded-lg text-indigo-600 border border-indigo-100 shadow-2xs mb-1.5">
                                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                            </svg>
                                                        </div>
                                                        <p class="text-xs font-semibold text-indigo-950 truncate w-full" title="{{ $file->getClientOriginalName() }}">
                                                            {{ $file->getClientOriginalName() }}
                                                        </p>
                                                    </div>

                                                    <!-- Footer Card: Informasi Ukuran File -->
                                                    <div class="mt-2 pt-2 border-t border-indigo-100 flex items-center justify-between text-[10px] text-indigo-400">
                                                        <span>Ukuran:</span>
                                                        <span class="font-medium text-indigo-600">{{ $sizeStr }}</span>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif
                            </div>

                            <div class="flex justify-end gap-2 pt-4 border-t border-slate-100">
                                <button type="button" wire:click="closeModal"
                                    class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-sm font-medium rounded-xl transition">
                                    Batal
                                </button>
                                <button type="submit"
                                    class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-xl transition shadow-sm shadow-indigo-600/30">
                                    Simpan
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Modal Konfirmasi Hapus -->
    @if ($isDeleteModalOpen)
        <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog"
            aria-modal="true">
            <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:p-0">
                <div class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm transition-opacity"
                    wire:click="$set('isDeleteModalOpen', false)"></div>

                <div
                    class="inline-block bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:max-w-lg sm:w-full p-6 border border-slate-200">
                    <div class="sm:flex sm:items-start">
                        <div
                            class="mx-auto shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-rose-100 sm:mx-0 sm:h-10 sm:w-10">
                            <svg class="h-6 w-6 text-rose-600" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                            <h3 class="text-lg font-bold text-slate-900">Hapus Subject?</h3>
                            <p class="text-sm text-slate-500 mt-2">
                                Apakah Anda yakin ingin menghapus subject ini? Tindakan ini tidak dapat dibatalkan.
                            </p>
                        </div>
                    </div>
                    <div class="mt-6 flex justify-end gap-2">
                        <button type="button" wire:click="$set('isDeleteModalOpen', false)"
                            class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-sm font-medium rounded-xl transition">
                            Batal
                        </button>
                        <button type="button" wire:click="delete"
                            class="px-4 py-2 bg-rose-600 hover:bg-rose-700 text-white text-sm font-medium rounded-xl transition shadow-sm shadow-rose-600/30">
                            Hapus
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>