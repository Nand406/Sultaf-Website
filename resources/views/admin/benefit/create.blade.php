<x-layouts.admin title="Tambah Promosi - Admin Sultaf" pageTitle="Tambah Promosi Baru">
    <form method="POST" action="{{ route('admin.benefit.store') }}">
        @include('admin.benefit._form')
    </form>
</x-layouts.admin>
