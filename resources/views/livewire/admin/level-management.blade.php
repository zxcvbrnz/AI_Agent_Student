<div class="py-12 max-w-7xl mx-auto sm:px-6 lg:px-8">
    <div class="bg-white border border-slate-200 text-slate-800 p-6 rounded-2xl shadow-sm">

        {{-- Header & Tombol Tambah --}}
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
            <div>
                <h2 class="text-2xl font-bold text-slate-900">Kelola Level & Paket</h2>
                <p class="text-sm text-slate-500">Atur daftar level, harga, dan prompt sistem untuk tiap tingkatan.</p>
            </div>
            <div class="flex items-center gap-3">
                <input type="text" wire:model.live="search" placeholder="Cari nama level / slug..."
                    class="bg-slate-50 border border-slate-200 text-slate-900 rounded-xl px-4 py-2 text-sm w-full sm:w-64 focus:outline-none focus:border-indigo-500">
                <button wire:click="openCreateModal"
                    class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-sm font-medium transition shadow-sm whitespace-nowrap">
                    + Tambah Level
                </button>
            </div>
        </div>

        {{-- Flash Message --}}
        @if (session()->has('message'))
            <div class="mb-4 p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl text-sm">
                {{ session('message') }}
            </div>
        @endif

        {{-- Tabel Daftar Level --}}
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-600">
                <thead class="bg-slate-50 text-slate-700 uppercase text-xs border-b border-slate-200">
                    <tr>
                        <th class="p-3">Nama Level</th>
                        <th class="p-3">Slug</th>
                        <th class="p-3">Harga (IDR)</th>
                        <th class="p-3">Prompt Singkat</th>
                        <th class="p-3 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($levels as $level)
                        <tr class="hover:bg-slate-50/80 align-top">
                            <td class="p-3 font-medium text-slate-900 flex items-center gap-2">
                                <span
                                    class="w-3 h-3 rounded-full bg-{{ $level->color ?? 'indigo' }}-500 inline-block"></span>
                                {{ $level->name }}
                            </td>
                            <td class="p-3 font-mono text-xs text-slate-500">
                                {{ $level->slug }}
                            </td>
                            <td class="p-3 font-semibold text-slate-700">
                                Rp {{ number_format($level->price, 0, ',', '.') }}
                            </td>
                            <td class="p-3 text-xs text-slate-500 max-w-xs truncate">
                                {{ $level->prompt ?? '-' }}
                            </td>
                            <td class="p-3 text-center">
                                <div class="flex items-center justify-center gap-2">
                                    <button wire:click="openEditModal({{ $level->id }})"
                                        class="px-3 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-lg text-xs font-medium transition">
                                        Edit
                                    </button>
                                    <button wire:click="delete({{ $level->id }})"
                                        wire:confirm="Yakin ingin menghapus level ini?"
                                        class="px-3 py-1.5 bg-rose-50 hover:bg-rose-100 text-rose-600 border border-rose-200 rounded-lg text-xs font-medium transition">
                                        Hapus
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="p-6 text-center text-slate-400 italic">Belum ada data level.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        <div class="mt-4">
            {{ $levels->links() }}
        </div>
    </div>

    {{-- MODAL FORM TAMBAH / EDIT --}}
    @if ($isModalOpen)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/50 backdrop-blur-sm p-4">
            <div class="bg-white rounded-2xl shadow-xl w-full max-w-lg overflow-hidden border border-slate-200">
                <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
                    <h3 class="font-bold text-lg text-slate-900">
                        {{ $isEditMode ? 'Edit Level' : 'Tambah Level Baru' }}
                    </h3>
                    <button wire:click="closeModal" class="text-slate-400 hover:text-slate-600">
                        ✕
                    </button>
                </div>

                <div class="p-6 space-y-4">
                    {{-- Nama Level --}}
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 uppercase mb-1">Nama Level</label>
                        <input type="text" wire:model.live="name" placeholder="Contoh: Pro Member, VIP"
                            class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2 text-sm focus:outline-none focus:border-indigo-500">
                        @error('name')
                            <span class="text-xs text-rose-600 mt-1 block">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- Slug --}}
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 uppercase mb-1">Slug</label>
                        <input type="text" wire:model="slug" placeholder="contoh: pro-member"
                            class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2 text-sm focus:outline-none focus:border-indigo-500 font-mono">
                        @error('slug')
                            <span class="text-xs text-rose-600 mt-1 block">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- Harga --}}
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 uppercase mb-1">Harga (IDR)</label>
                        <input type="number" wire:model="price" placeholder="50000"
                            class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2 text-sm focus:outline-none focus:border-indigo-500">
                        @error('price')
                            <span class="text-xs text-rose-600 mt-1 block">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- Warna Badge --}}
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 uppercase mb-1">Warna Badge</label>
                        <select wire:model="color"
                            class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2 text-sm focus:outline-none focus:border-indigo-500">
                            <option value="indigo">Indigo (Ungu Biru)</option>
                            <option value="emerald">Emerald (Hijau)</option>
                            <option value="amber">Amber (Kuning/Oranye)</option>
                            <option value="rose">Rose (Merah Muda)</option>
                            <option value="purple">Purple (Ungu)</option>
                            <option value="blue">Blue (Biru)</option>
                        </select>
                        @error('color')
                            <span class="text-xs text-rose-600 mt-1 block">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- Prompt --}}
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 uppercase mb-1">Prompt / Hak Akses
                            Sistem</label>
                        <textarea wire:model="prompt" rows="3" placeholder="Deskripsi atau konfigurasi prompt khusus untuk level ini..."
                            class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2 text-sm focus:outline-none focus:border-indigo-500"></textarea>
                        @error('prompt')
                            <span class="text-xs text-rose-600 mt-1 block">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="px-6 py-4 bg-slate-50 border-t border-slate-100 flex justify-end gap-2">
                    <button wire:click="closeModal"
                        class="px-4 py-2 bg-white border border-slate-200 hover:bg-slate-100 text-slate-700 rounded-xl text-sm font-medium transition">
                        Batal
                    </button>
                    <button wire:click="save"
                        class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-sm font-medium transition shadow-sm">
                        Simpan
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
