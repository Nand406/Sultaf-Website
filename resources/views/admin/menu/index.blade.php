<x-layouts.admin title="Kelola Menu - Admin Sultaf" pageTitle="Kelola Menu">

    @if (session('success'))
        <div class="mb-6 bg-sultaf-success-soft border border-sultaf-success/20 text-sultaf-success text-sm rounded-xl px-4 py-3">
            ✓ {{ session('success') }}
        </div>
    @endif

    {{-- Header dengan Search & Tombol Tambah --}}
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
        <form method="GET" action="{{ route('admin.menu.index') }}" class="w-full sm:w-80">
            <input type="text" name="search" value="{{ request('search') }}" 
                   placeholder="Cari nama menu..." 
                   class="w-full border border-sultaf-border rounded-xl px-4 py-2.5 text-sm bg-white focus:outline-none focus:border-sultaf-maroon">
        </form>
        <a href="{{ route('admin.menu.create') }}" class="bg-sultaf-maroon hover:bg-sultaf-maroon-dark text-white text-sm font-semibold rounded-xl px-5 py-2.5 transition-colors shrink-0">
            + Tambah Menu
        </a>
    </div>

    {{-- Tabel Menu --}}
    <div class="staff-card overflow-hidden">
        <table class="w-full text-sm">
            <thead>
                <tr class="text-left text-sultaf-muted text-xs uppercase bg-sultaf-cream/60">
                    <th class="px-5 py-3 font-medium">Menu</th>
                    <th class="px-5 py-3 font-medium">Kategori</th>
                    <th class="px-5 py-3 font-medium">Harga</th>
                    <th class="px-5 py-3 font-medium">Status</th>
                    <th class="px-5 py-3 font-medium text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-sultaf-border">
                @forelse ($menus as $menu)
                    <tr>
                        <td class="px-5 py-3">
                            <div class="flex items-center gap-3">
                                @if ($menu->foto_menu)
                                    <img src="{{ asset($menu->foto_menu) }}" alt="{{ $menu->nama_menu }}" 
                                         class="w-10 h-10 rounded-lg object-cover border border-sultaf-border">
                                @else
                                    <div class="w-10 h-10 rounded-lg bg-sultaf-cream flex items-center justify-center text-sultaf-muted text-xs">
                                        No Img
                                    </div>
                                @endif
                                <span class="font-semibold text-sultaf-ink">{{ $menu->nama_menu }}</span>
                            </div>
                        </td>
                        <td class="px-5 py-3 text-sultaf-muted capitalize">
                            {{ $menu->kategori ?? '-' }}
                        </td>
                        <td class="px-5 py-3 text-sultaf-ink">
                            Rp {{ number_format($menu->harga, 0, ',', '.') }}
                        </td>
                        <td class="px-5 py-3">
                            <span class="text-xs font-semibold px-2.5 py-1 rounded-full
                                {{ $menu->status_ketersediaan === 'tersedia' 
                                    ? 'bg-sultaf-success-soft text-sultaf-success' 
                                    : 'bg-sultaf-danger-soft text-sultaf-danger' }}">
                                {{ ucfirst($menu->status_ketersediaan) }}
                            </span>
                        </td>
                        <td class="px-5 py-3 text-right">
                            <div class="flex items-center justify-end gap-3">
                                <a href="{{ route('admin.menu.edit', $menu) }}" class="text-blue-600 hover:underline text-sm font-medium">Edit</a>
                                
                                {{-- DIUBAH: Menambahkan nama menu ke dalam parameter onclick --}}
                                <form id="delete-form-{{ $menu->id_menu }}" method="POST" action="{{ route('admin.menu.destroy', $menu) }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" 
                                            onclick="openDeleteModal('delete-form-{{ $menu->id_menu }}', '{{ addslashes($menu->nama_menu) }}')" 
                                            class="text-sultaf-danger hover:underline text-sm font-medium">
                                        Hapus
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-5 py-12 text-center text-sultaf-muted">
                            Belum ada menu yang ditambahkan.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    @if ($menus->hasPages())
        <div class="mt-5">
            {{ $menus->links() }}
        </div>
    @endif

    {{-- ===================== CUSTOM DELETE MODAL ===================== --}}
    <div id="deleteModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 backdrop-blur-sm transition-opacity">
        <div class="bg-white rounded-2xl p-6 w-full max-w-sm mx-4 shadow-2xl">
            
            <div class="text-center">
                {{-- Ikon Peringatan --}}
                <div class="w-16 h-16 rounded-full bg-red-100 flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                </div>
                
                <h3 class="text-xl font-bold text-sultaf-ink mb-2">Hapus Menu ?</h3>
                
                {{-- DIUBAH: Menambahkan elemen untuk menampilkan nama menu --}}
                <p class="text-sm text-sultaf-muted mb-6">
                    Apakah Anda yakin ingin menghapus menu <strong id="deleteMenuName" class="text-sultaf-ink"></strong> ?<br>
                </p>
            </div>

            {{-- Tombol Aksi --}}
            <div class="flex gap-3">
                <button onclick="closeDeleteModal()" class="flex-1 py-2.5 rounded-xl border border-sultaf-border text-sultaf-ink font-semibold hover:bg-sultaf-cream transition-colors">
                    Batal
                </button>
                
                {{-- DIUBAH: Menambahkan inline style (style="...") agar tombol dan teks dijamin muncul --}}
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

        // DIUBAH: Menambahkan parameter menuName
        function openDeleteModal(formId, menuName) {
            formToSubmit = document.getElementById(formId);
            
            // DIUBAH: Mengisi teks nama menu ke dalam modal
            document.getElementById('deleteMenuName').textContent = menuName;
            
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