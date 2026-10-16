<div class="py-12 max-w-7xl mx-auto sm:px-6 lg:px-8">
    <div class="bg-white border border-slate-200 text-slate-800 p-6 rounded-2xl shadow-sm">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
            <h2 class="text-2xl font-bold text-slate-900">Kelola Membership Level User (Admin)</h2>
            <input type="text" wire:model.live="search" placeholder="Cari nama / email..."
                class="bg-slate-50 border border-slate-200 text-slate-900 rounded-xl px-4 py-2 text-sm w-full sm:w-64 focus:outline-none focus:border-indigo-500">
        </div>

        {{-- Kontrol Pilihan Level untuk Aksi Perpanjangan/Expired --}}
        <div
            class="mb-6 p-4 bg-slate-50 border border-slate-200 rounded-xl flex flex-col sm:flex-row items-center gap-4">
            <div class="w-full sm:w-auto flex-1">
                <label class="block text-xs font-semibold text-slate-700 uppercase mb-1">Pilih Level Target untuk Aksi
                    Tombol:</label>
                <select wire:model="selectedLevelId"
                    class="w-full bg-white border border-slate-200 text-slate-900 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-indigo-500">
                    <option value="">-- Pilih Level --</option>
                    @foreach ($levels as $lvl)
                        <option value="{{ $lvl->id }}">{{ $lvl->name }}</option>
                    @endforeach
                </select>
                @error('selectedLevelId')
                    <span class="text-xs text-rose-600 mt-1 block">{{ $message }}</span>
                @enderror
            </div>
        </div>

        @if (session()->has('message'))
            <div class="mb-4 p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl text-sm">
                {{ session('message') }}
            </div>
        @endif

        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-600">
                <thead class="bg-slate-50 text-slate-700 uppercase text-xs border-b border-slate-200">
                    <tr>
                        <th class="p-3">User</th>
                        <th class="p-3">Role</th>
                        <th class="p-3">Membership per Level</th>
                        <th class="p-3 text-center">Aksi (Berdasarkan Level Terpilih)</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach ($users as $user)
                        <tr class="hover:bg-slate-50/80 align-top">
                            <td class="p-3 font-medium text-slate-900">
                                {{ $user->name }}
                                <div class="text-xs text-slate-400">{{ $user->email }}</div>
                            </td>
                            <td class="p-3">
                                <span
                                    class="px-2.5 py-1 rounded-md text-xs font-semibold {{ $user->isAdmin() ? 'bg-purple-100 text-purple-700' : 'bg-slate-100 text-slate-600' }}">
                                    {{ strtoupper($user->role) }}
                                </span>
                            </td>
                            <td class="p-3">
                                @if ($user->levels->count() > 0)
                                    <div class="space-y-2">
                                        @foreach ($user->levels as $level)
                                            <div
                                                class="flex flex-wrap items-center gap-2 bg-slate-50 p-2 rounded-lg border border-slate-100 text-xs">
                                                <span class="font-semibold text-slate-900">{{ $level->name }}:</span>

                                                {{-- Badge Tipe Membership Pivot --}}
                                                @php
                                                    $badgeColor = match ($level->pivot->membership_type) {
                                                        'pro' => 'bg-indigo-100 text-indigo-700',
                                                        'free_trial' => 'bg-amber-100 text-amber-700',
                                                        default => 'bg-slate-200 text-slate-700',
                                                    };
                                                @endphp
                                                <span
                                                    class="uppercase font-semibold px-2 py-0.5 rounded {{ $badgeColor }}">
                                                    {{ str_replace('_', ' ', $level->pivot->membership_type) }}
                                                </span>

                                                <span class="font-mono text-slate-500">
                                                    ({{ $level->pivot->membership_expires_at ? Carbon\Carbon::parse($level->pivot->membership_expires_at)->format('d M Y, H:i') : '-' }})
                                                </span>

                                                {{-- Status Aktif/Expired per Level --}}
                                                @php
                                                    $isLevelActive =
                                                        $user->isAdmin() ||
                                                        ($level->pivot->membership_expires_at &&
                                                            Carbon\Carbon::parse(
                                                                $level->pivot->membership_expires_at,
                                                            )->isFuture());
                                                @endphp

                                                @if ($isLevelActive)
                                                    <span
                                                        class="px-2 py-0.5 bg-emerald-100 text-emerald-700 font-semibold rounded-full">Aktif</span>
                                                @else
                                                    <span
                                                        class="px-2 py-0.5 bg-rose-100 text-rose-700 font-semibold rounded-full">Expired</span>
                                                @endif
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <span class="text-xs text-slate-400 italic">Belum memiliki level/membership</span>
                                @endif
                            </td>
                            <td class="p-3 text-center">
                                <div class="flex items-center justify-center gap-2">
                                    <button wire:click="extendMembership({{ $user->id }})"
                                        class="px-3 py-1.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg text-xs font-medium transition shadow-sm">
                                        +30 Hari Pro (Level Dipilih)
                                    </button>
                                    <button wire:click="setExpired({{ $user->id }})"
                                        class="px-3 py-1.5 bg-rose-50 hover:bg-rose-100 text-rose-600 border border-rose-200 rounded-lg text-xs font-medium transition">
                                        Set Expired (Level Dipilih)
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $users->links() }}
        </div>
    </div>
</div>
