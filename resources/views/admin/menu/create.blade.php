<x-layouts.admin title="Tambah Menu - Admin Sultaf" pageTitle="Tambah Menu Baru">

    <form method="POST" action="{{ route('admin.menu.store') }}" enctype="multipart/form-data">
        @include('admin.menu._form')
    </form>
</x-layouts.admin>
