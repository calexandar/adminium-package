@php($active = $item->isActive())

<li>
  <a href="{{ $item->url() }}"
    @class([
      'group flex gap-x-3 rounded-md p-2 text-sm/6 font-semibold text-gray-700 hover:bg-gray-50 hover:text-indigo-600 dark:text-gray-400 dark:hover:bg-white/5 dark:hover:text-white',
      'bg-gray-50 text-indigo-600 dark:bg-white/5 dark:text-white' => $active,
    ])
  >
    @include('dashboard::partials.navigation-icon', ['name' => $item->icon, 'active' => $active])
    {{ $item->label }}
  </a>
</li>
