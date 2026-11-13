@foreach ($navigationGroups as $navigationGroup)
  @php($items = array_filter(
      $navigationGroup['items'],
      static fn ($item): bool => $item->permission === null || \Illuminate\Support\Facades\Gate::allows($item->permission),
  ))

  @if ($items !== [])
    @if ($navigationGroup['group']->id === 'main')
      <li>
        <ul role="list" class="-mx-2 space-y-1">
          @foreach ($items as $item)
            @include('dashboard::partials.navigation-item', ['item' => $item])
          @endforeach
        </ul>
      </li>
    @else
      <li>
        <div class="text-xs/6 font-semibold text-gray-400">{{ $navigationGroup['group']->label }}</div>
        <ul role="list" class="-mx-2 mt-2 space-y-1">
          @foreach ($items as $item)
            @include('dashboard::partials.navigation-item', ['item' => $item])
          @endforeach
        </ul>
      </li>
    @endif
  @endif
@endforeach
