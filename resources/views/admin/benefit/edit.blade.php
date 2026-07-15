<x-layouts.admin title="Edit Promosi - Admin Sultaf" pageTitle="Edit Promosi">
    <form method="POST" action="{{ route('admin.benefit.update', $benefit) }}">
        @method('PUT')
        @include('admin.benefit._form')
    </form>
</x-layouts.admin>
