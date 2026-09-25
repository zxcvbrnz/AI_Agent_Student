<x-app-layout>
    <div class="py-16 max-w-2xl mx-auto px-4 text-center">
        <div class="bg-white border border-slate-200 p-8 rounded-2xl shadow-xl">
            <div
                class="w-16 h-16 bg-rose-100 text-rose-600 rounded-full flex items-center justify-center text-3xl mx-auto mb-4">
                🔒
            </div>
            <h1 class="text-2xl font-bold text-slate-900 mb-2">Masa Aktif Paket Kamu Telah Habis</h1>
            <p class="text-slate-600 text-sm mb-6">
                Uji coba gratis atau masa langganan bulanan kamu telah berakhir. Lakukan transfer manual untuk
                mengaktifkan kembali akses AI Tutor.
            </p>

            <!-- Informasi Pembayaran Manual -->
            <div class="bg-slate-50 p-5 rounded-xl border border-slate-200 text-left mb-6 space-y-3">
                <h3 class="text-xs font-bold text-indigo-600 uppercase tracking-wider">Metode Pembayaran Manual</h3>
                <div class="text-sm text-slate-700 space-y-1">
                    <p>💳 <strong>Bank BCA:</strong> 1234-5678-90 (a.n. Admin EduAI)</p>
                    <p>📱 <strong>DANA / GoPay:</strong> 0812-3456-7890</p>
                    <p>💵 <strong>Biaya Membership:</strong> Rp 29.000 / Bulan</p>
                </div>
            </div>

            <p class="text-xs text-slate-500 mb-6">
                Kirim bukti transfer ke WhatsApp Admin berikut agar akunmu langsung diaktifkan kembali:
            </p>

            <a href="https://wa.me/6281234567890?text=Halo%20Admin,%20saya%20sudah%20transfer%20membership%20EduAI.%20Email:%20{{ urlencode(auth()->user()->email) }}"
                target="_blank"
                class="inline-flex items-center gap-2 px-6 py-3 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-semibold shadow-md shadow-emerald-600/20 transition">
                <span>💬 Konfirmasi via WhatsApp Admin</span>
            </a>
        </div>
    </div>
</x-app-layout>
