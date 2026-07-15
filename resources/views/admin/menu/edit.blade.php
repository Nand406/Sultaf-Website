<x-layouts.admin title="Edit Menu - Admin Sultaf" pageTitle="Edit Menu: {{ $menu->nama_makanan }}">

    <form method="POST" action="{{ route('admin.menu.update', $menu) }}" enctype="multipart/form-data">
        @method('PUT')
        @include('admin.menu._form')
    </form>
</x-layouts.admin>
