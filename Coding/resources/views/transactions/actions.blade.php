<div class="flex justify-center gap-2">
    {{-- View --}}
    @can('view', $t)
        <a href="{{ route('transactions.show', $t) }}"
           class="px-4 py-2 bg-sky-600 hover:bg-sky-700 text-white rounded-mde">
            View
        </a>
    @endcan

    {{-- Delete --}}
    @can('delete', $t)
        <form action="{{ route('transactions.destroy', $t) }}"
              method="POST"
              onsubmit="return confirm('Delete this transaction?')">
            @csrf
            @method('DELETE')
            <button
                class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-md">
                Delete
            </button>
        </form>
    @endcan
</div>
