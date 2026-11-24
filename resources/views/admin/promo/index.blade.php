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
                        <td class="px-5 py-3 text-sultaf-muted">{{ \Str::limit($notif->isi_pesan, 50) }}</td>
                        <td class="px-5 py-3 text-sultaf-ink">{{ $notif->user->username ?? 'N/A' }}</td>
                        
                        {{-- DIUBAH: Menambahkan pengecekan null pada created_at --}}
                        <td class="px-5 py-3 text-sultaf-muted">
                            {{ $notif->created_at ? $notif->created_at->format('d M Y H:i') : '-' }}
                        </td>
                        
                        <td class="px-5 py-3 text-right">
                            <form id="delete-notif-form-{{ $notif->id_notifikasi }}" method="POST" action="{{ route('admin.promo.destroy', $notif) }}">
                                @csrf
                                @method('DELETE')
                                <button type="button" 
                                        onclick="openDeleteModal('delete-notif-form-{{ $notif->id_notifikasi }}', '{{ addslashes($notif->judul) }}')" 
                                        class="text-sultaf-danger hover:underline text-sm font-medium">
                                    Hapus
                                </button>
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

    {{-- ===================== CUSTOM DELETE MODAL ===================== --}}
    <div id="deleteModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 backdrop-blur-sm transition-opacity">
        <div class="bg-white rounded-2xl p-6 w-full max-w-sm mx-4 shadow-2xl">
            
            <div class="text-center">
                <div class="w-16 h-16 rounded-full bg-red-100 flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-sultaf-ink mb-2">Hapus Notifikasi?</h3>
                <p class="text-sm text-sultaf-muted mb-6">
                    Apakah Anda yakin ingin menghapus notifikasi <strong id="deleteItemName" class="text-sultaf-ink"></strong>?<br>Tindakan ini tidak dapat dibatalkan.
                </p>
            </div>

            <div class="flex gap-3">
                <button onclick="closeDeleteModal()" class="flex-1 py-2.5 rounded-xl border border-sultaf-border text-sultaf-ink font-semibold hover:bg-sultaf-cream transition-colors">
                    Batal
                </button>
                <button onclick="submitDelete()" 
                        style="background-color: #dc2626; color: #ffffff;" 
                        class="flex-1 py-2.5 rounded-xl font-semibold hover:bg-red-700 shadow-sm transition-colors">
                    Ya
                </button>
            </div>
        </div>
    </div>

    {{-- SCRIPT UNTUK MODAL --}}
    <script>
        let formToSubmit = null;

        function openDeleteModal(formId, itemName) {
            formToSubmit = document.getElementById(formId);
            document.getElementById('deleteItemName').textContent = itemName;
            const modal = document.getElementById('deleteModal');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        function closeDeleteModal() {
            formToSubmit = null;
            const modal = document.getElementById('deleteModal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }

        function submitDelete() {
            if (formToSubmit) {
                formToSubmit.submit();
            }
        }
    </script>

</x-layouts.admin>