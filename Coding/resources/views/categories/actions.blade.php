<div class="flex gap-2">
    @can('update', $cat)
        <a href="{{ route('categories.edit', $cat) }}"
           class="px-4 py-2 bg-yellow-600 hover:bg-yellow-700 text-white rounded-md">
            Edit
        </a>
    @endcan

    @can('delete', $cat)
        <form action="{{ route('categories.destroy', $cat) }}" method="POST">
            @csrf
            @method('DELETE')
            <button onclick="return confirm('Delete?')"
                    class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-md">
                Delete
            </button>
        </form>
    @endcan
</div>
