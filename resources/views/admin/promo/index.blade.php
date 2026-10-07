<x-layouts.admin title="Kirim Notifikasi - Admin Sultaf" pageTitle="Kirim Notifikasi">

    @if (session('success'))
        <div class="mb-6 bg-sultaf-success-soft border border-sultaf-success/20 text-sultaf-success text-sm rounded-xl px-4 py-3">
            ✓ {{ session('success') }}
        </div>
    @endif

    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
        <p class="text-sm text-sultaf-muted max-w-xl">
            Kirim notifikasi ke member atau customer. Notifikasi akan muncul di halaman mereka.
        </p>
        <a href="{{ route('admin.promo.create') }}"
           class="bg-sultaf-maroon hover:bg-sultaf-maroon-dark text-white text-sm font-semibold rounded-xl px-5 py-2.5 transition-colors shrink-0">
            + Kirim Notifikasi
        </a>
    </div>

    <div class="staff-card overflow-hidden">
        <table class="w-full text-sm">
            <thead>
                <tr class="text-left text-sultaf-muted text-xs uppercase bg-sultaf-cream/60">
                    <th class="px-5 py-3 font-medium">Judul</th>
                    <th class="px-5 py-3 font-medium">Pesan</th>
                    <th class="px-5 py-3 font-medium">Penerima</th>
                    <th class="px-5 py-3 font-medium">Tanggal</th>
                    <th class="px-5 py-3 font-medium text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-sultaf-border">
                @forelse ($notifikasis as $notif)
                    <tr>
                        <td class="px-5 py-3 font-semibold text-sultaf-ink">{{ $notif->judul }}</td>
                        <td class="px-5 py-3 text-sultaf-muted">{{ \Str::limit($notif->id_pesan, 50) }}</td>
                        <td class="px-5 py-3 text-sultaf-ink">{{ $notif->user->username ?? 'N/A' }}</td>
                        <td class="px-5 py-3 text-sultaf-muted">{{ $notif->created_at->format('d M Y H:i') }}</td>
                        <td class="px-5 py-3 text-right">
                            <form method="POST" action="{{ route('admin.promo.destroy', $notif) }}"
                                  onsubmit="return confirm('Hapus notifikasi ini?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-sultaf-danger hover:underline text-sm font-medium">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-5 py-12 text-center text-sultaf-muted">Belum ada notifikasi yang dikirim.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if ($notifikasis->hasPages())
        <div class="mt-5">{{ $notifikasis->links() }}</div>
    @endif

</x-layouts.admin>