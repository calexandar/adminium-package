@extends('dashboard::admin_layout')

@section('content')
<div class="px-4 sm:px-6 lg:px-8">
  @if($media->isEmpty())
    <div class="text-center">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" aria-hidden="true" class="mx-auto size-12 text-gray-400 dark:text-gray-500">
        <path d="M9 13h6m-3-3v6m-9 1V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z" stroke-width="2" vector-effect="non-scaling-stroke" stroke-linecap="round" stroke-linejoin="round" />
      </svg>
      <h3 class="mt-2 text-sm font-semibold text-gray-900 dark:text-white">No media</h3>
      <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Get started by creating a new item.</p>
      <div class="mt-6">
        <a href="{{ route('admin.media.create') }}" class="inline-flex items-center rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-xs hover:bg-indigo-500 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600 dark:bg-indigo-500 dark:shadow-none dark:hover:bg-indigo-400 dark:focus-visible:outline-indigo-500">
          <svg viewBox="0 0 20 20" fill="currentColor" data-slot="icon" aria-hidden="true" class="mr-1.5 -ml-0.5 size-5">
            <path d="M10.75 4.75a.75.75 0 0 0-1.5 0v4.5h-4.5a.75.75 0 0 0 0 1.5h4.5v4.5a.75.75 0 0 0 1.5 0v-4.5h4.5a.75.75 0 0 0 0-1.5h-4.5v-4.5Z" />
          </svg>
          New Media
        </a>
      </div>
    </div>
  @else
  <div class="sm:flex sm:items-center">
    <div class="sm:flex-auto">
      <h1 class="text-base font-semibold text-gray-900 dark:text-white">Media</h1>
      <p class="mt-2 text-sm text-gray-700 dark:text-gray-300">A list of all the medias.</p>
    </div>
    <div class="mt-4 sm:mt-0 sm:ml-16 sm:flex-none">
      <a href="{{ route('admin.media.create') }}" class="block rounded-md bg-indigo-600 px-3 py-2 text-center text-sm font-semibold text-white shadow-xs hover:bg-indigo-500 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600 dark:bg-indigo-500 dark:hover:bg-indigo-400 dark:focus-visible:outline-indigo-500">Add Media</a>
    </div>
  </div>
    <div class="mt-8 flow-root">
      <div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
        <div class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8">
          <div class="overflow-hidden shadow-sm outline-1 outline-black/5 sm:rounded-lg dark:shadow-none dark:-outline-offset-1 dark:outline-white/10">
            <table class="relative min-w-full divide-y divide-gray-300 dark:divide-white/15">
              <thead class="bg-gray-50 dark:bg-gray-800/75">
                <tr>
                  <th scope="col" class="py-3.5 pr-3 pl-4 text-left text-sm font-semibold text-gray-900 sm:pl-6 dark:text-gray-200">Sort</th>
                  <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900 dark:text-gray-200">Title</th>
                  <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900 dark:text-gray-200">Slug</th>
                  <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900 dark:text-gray-200">File</th>
                  <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900 dark:text-gray-200">Status</th>
                  <th scope="col" class="py-3.5 pr-4 pl-3 sm:pr-6">
                    <span class="sr-only">Edit</span>
                  </th>
                </tr>
              </thead>
              <tbody id="sortable" class="divide-y divide-gray-200 bg-white dark:divide-white/10 dark:bg-gray-800/50">
                @foreach ($media as $item )
                    <tr class="item" data-id="{{ $item->id }}">
                      <td class="py-4 pr-3 pl-4 text-sm font-medium whitespace-nowrap text-gray-900 sm:pl-6 dark:text-white"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 12a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0ZM12.75 12a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0ZM18.75 12a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Z" /></svg></td>
                      <td class="px-3 py-4 text-sm whitespace-nowrap text-gray-500 dark:text-gray-400">{{ $item->title }}</td>
                      <td class="px-3 py-4 text-sm whitespace-nowrap text-gray-500 dark:text-gray-400">{{ $item->slug }}</td>
                      <td class="px-3 py-4 text-sm whitespace-nowrap text-gray-500 dark:text-gray-400">
                        @php $file = $item->getFirstMedia('media'); @endphp
                        @if ($file)
                          <a href="{{ $file->getUrl() }}" target="_blank" class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300">{{ strtoupper($file->extension) }}</a>
                        @else
                          —
                        @endif
                      </td>
                      <td class="px-3 py-4 text-sm whitespace-nowrap text-gray-500 dark:text-gray-400">
                        @if ($item->published)
                          <span class="inline-flex items-center rounded-md bg-green-50 px-2 py-1 text-xs font-medium text-green-700 ring-1 ring-green-600/20 ring-inset dark:bg-green-900/30 dark:text-green-400 dark:ring-green-500/50">Published</span>
                        @else
                          <span class="inline-flex items-center rounded-md bg-neutral-50 px-2 py-1 text-xs font-medium text-neutral-700 ring-1 ring-neutral-600/20 ring-inset dark:bg-neutral-900/30 dark:text-neutral-400 dark:ring-neutral-500/50">Draft</span>
                        @endif
                      </td>
                      <td class="py-4 pr-4 pl-3 text-right text-sm font-medium whitespace-nowrap sm:pr-6">
                        <a href="{{ route('admin.media.edit', $item) }}" class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300">Edit<span class="sr-only">, </span></a>
                        <span>/</span>
                        <div x-data="{}" class="inline-block">
                          <form x-ref="deleteForm" :action="`{{ route('admin.media.destroy', $item) }}`" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="button"
                              @click="Swal.fire({
                                title: 'Are you sure?',
                                text: 'You won\'t be able to revert this!',
                                icon: 'warning',
                                showCancelButton: true,
                                confirmButtonColor: '#3085d6',
                                cancelButtonColor: '#d33',
                                confirmButtonText: 'Yes, delete it!'
                              }).then((result) => {
                                if (result.isConfirmed) {
                                  $refs.deleteForm.submit();
                                }
                              })"
                              class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300">Delete<span class="sr-only">, </span></button>
                          </form>
                        </div>
                      </td>
                    </tr>                 
                @endforeach               
              </tbody>
            </table>
            {{ $media->links() }}
          </div>
        </div>
      </div>
    </div>
  @endif
</div>


@endsection

@push('admin_script')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const sortableTableBody = document.getElementById('sortable');

    new Sortable(sortableTableBody, {
        animation: 150, // Animation speed in milliseconds
        ghostClass: '.item', // Class for the ghost element
        onEnd: function (evt) {
            // This function is called when a drag-and-drop operation ends
            const newOrder = [];
            sortableTableBody.querySelectorAll('tr').forEach((row, index) => {
                newOrder.push({
                    id: row.dataset.id,
                    order: index + 1 // Assign new order based on current position
                });
            });

            // Send the new order to your backend
            saveOrderToServer(newOrder);
        }
    });

    function saveOrderToServer(orderData) {
        // Example using Fetch API for an AJAX request
        fetch('{{ route('admin.news.reorder') }}', { // Replace with your actual route
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}' // If using Laravel/similar frameworks
            },
            body: JSON.stringify({ order: orderData })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                console.log('Order saved successfully:', data);
                // Optionally update the displayed order numbers in the table
                sortableTableBody.querySelectorAll('tr').forEach((row, index) => {
                    row.querySelector('td:first-child').textContent = index + 1;
                });
            } else {
                console.error('Error saving order:', data);
            }
        })
        .catch(error => {
            console.error('Network error or server error:', error);
        });
    }
});
</script>
@endpush