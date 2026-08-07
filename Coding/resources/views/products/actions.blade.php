<div class="flex gap-2">
    @can('update', $p)
        <a href="{{ route('products.edit', $p) }}"
           class="px-4 py-2 bg-yellow-600 hover:bg-yellow-700 text-white rounded-md">
            Edit
        </a>
    @endcan

    @can('delete', $p)
        <form action="{{ route('products.destroy', $p) }}" method="POST">
            @csrf
            @method('DELETE')
            <button onclick="return confirm('Delete?')"
                    class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-md">
                Delete
            </button>
        </form>
    @endcan
</div>
