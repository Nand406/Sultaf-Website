<x-layouts.admin title="Kirim Pesan Promo - Admin Sultaf" pageTitle="Kirim Pesan Promosi">

    @if (session('success'))
        <div class="mb-6 bg-emerald-50 border border-emerald-200 text-emerald-700 text-sm rounded-xl px-4 py-3">
            ✓ {{ session('success') }}
        </div>
    @endif

    <div class="flex flex-col lg:flex-row gap-6 items-start">

        <div class="w-full lg:w-96 shrink-0">
            <div class="bg-white rounded-2xl border border-gray-100 p-6">
                <h3 class="font-semibold text-gray-800 mb-4">Pesan Baru</h3>

                <form method="POST" action="{{ route('admin.promo.store') }}" class="space-y-4">
                    @csrf

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Judul</label>
                        <input type="text" name="judul" required placeholder="e.g. Promo Akhir Pekan!"
                               class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-sultaf-maroon">
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Isi Pesan</label>
                        <textarea name="pesan" rows="4" required placeholder="Tulis pesan promosi di sini..."
                                  class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-sultaf-maroon"></textarea>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Kirim Kepada</label>
                        <select name="target_role" required
                                class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-sultaf-maroon">
                            <option value="member">Member saja ({{ $jumlahMember }} orang)</option>
                            <option value="semua">Semua pengguna terdaftar ({{ $jumlahSemua }} orang)</option>
                        </select>
                        <p class="text-xs text-gray-400 mt-1">
                            Semua member mendapat promosi yang sama — tidak ada pembagian tingkatan.
                        </p>
                    </div>

                    <button type="submit"
                            class="w-full bg-sultaf-maroon hover:bg-sultaf-maroon-dark text-white font-semibold rounded-xl py-3 text-sm transition-colors">
                        Kirim Pesan
                    </button>
                </form>
            </div>
        </div>

        <div class="flex-1 min-w-0 w-full">
            <h3 class="font-semibold text-gray-800 mb-4">Riwayat Pesan Terkirim</h3>

            <div class="space-y-3">
                @forelse ($pesanTerkirim as $pesan)
                    <div class="bg-white rounded-2xl border border-gray-100 p-5">
                        <div class="flex items-start justify-between mb-2">
                            <div>
                                <h4 class="font-semibold text-gray-800">{{ $pesan->judul }}</h4>
                                <p class="text-xs text-gray-400 mt-0.5">
                                    Dikirim {{ $pesan->created_at->diffForHumans() }}
                                    oleh {{ $pesan->pengirim->name ?? 'Admin' }}
                                </p>
                            </div>
                            <form method="POST" action="{{ route('admin.promo.destroy', $pesan) }}"
                                  onsubmit="return confirm('Hapus pesan ini?')">
                                @csrf @method('DELETE')
                                <button class="text-xs text-gray-400 hover:text-red-500">Hapus</button>
                            </form>
                        </div>
                        <p class="text-sm text-gray-600 mb-3">{{ $pesan->pesan }}</p>
                        <span class="text-xs font-semibold bg-sultaf-maroon/10 text-sultaf-maroon px-2.5 py-1 rounded-full">
                            {{ $pesan->target_role === 'semua' ? 'Semua Pengguna' : 'Member' }}
                        </span>
                    </div>
                @empty
                    <div class="bg-white rounded-2xl border border-gray-100 p-16 text-center text-gray-400">
                        Belum ada pesan promosi yang dikirim.
                    </div>
                @endforelse
            </div>

            <div class="mt-5">{{ $pesanTerkirim->links() }}</div>
        </div>
    </div>
</x-layouts.admin>
