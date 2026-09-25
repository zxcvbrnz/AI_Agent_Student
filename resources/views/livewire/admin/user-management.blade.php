<div class="py-12 max-w-7xl mx-auto sm:px-6 lg:px-8">
    <div class="bg-white border border-slate-200 text-slate-800 p-6 rounded-2xl shadow-sm">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
            <h2 class="text-2xl font-bold text-slate-900">Kelola Membership User (Admin)</h2>
            <input type="text" wire:model.live="search" placeholder="Cari nama / email..."
                class="bg-slate-50 border border-slate-200 text-slate-900 rounded-xl px-4 py-2 text-sm w-full sm:w-64 focus:outline-none focus:border-indigo-500">
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
                        <th class="p-3">Paket</th>
                        <th class="p-3">Masa Aktif / Expired</th>
                        <th class="p-3">Status</th>
                        <th class="p-3 text-center">Aksi (Kelola Masa Aktif)</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach ($users as $user)
                        <tr class="hover:bg-slate-50/80">
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
                                <span
                                    class="uppercase text-xs font-semibold px-2.5 py-1 rounded-md bg-indigo-100 text-indigo-700">
                                    {{ $user->membership_type }}
                                </span>
                            </td>
                            <td class="p-3 font-mono text-xs text-slate-600">
                                {{ $user->membership_expires_at ? $user->membership_expires_at->format('d M Y, H:i') : '-' }}
                            </td>
                            <td class="p-3">
                                @if ($user->hasActiveMembership())
                                    <span
                                        class="px-2.5 py-1 bg-emerald-100 text-emerald-700 text-xs font-semibold rounded-full">Aktif</span>
                                @else
                                    <span
                                        class="px-2.5 py-1 bg-rose-100 text-rose-700 text-xs font-semibold rounded-full">Expired</span>
                                @endif
                            </td>
                            <td class="p-3 text-center">
                                <div class="flex items-center justify-center gap-2">
                                    <button wire:click="extendMembership({{ $user->id }})"
                                        class="px-3 py-1.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg text-xs font-medium transition shadow-sm">
                                        +30 Hari Pro
                                    </button>
                                    <button wire:click="setExpired({{ $user->id }})"
                                        class="px-3 py-1.5 bg-rose-50 hover:bg-rose-100 text-rose-600 border border-rose-200 rounded-lg text-xs font-medium transition">
                                        Set Expired
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
