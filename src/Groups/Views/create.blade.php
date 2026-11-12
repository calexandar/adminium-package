@extends('dashboard::admin_layout')

@section('content')

@php
  $locales = adminium_locales();
  $defaultLocale = $locales[0] ?? 'en';
  $inputClass = 'block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6 dark:bg-white/5 dark:text-white dark:outline-white/10 dark:placeholder:text-gray-500 dark:focus:outline-indigo-500';
  $labelClass = 'block text-sm/6 font-medium text-gray-900 dark:text-white';
  $helpClass = 'mt-2 text-sm text-gray-500 dark:text-gray-400';
  $errorClass = 'mt-2 text-sm text-red-600 dark:text-red-400';
  $cardClass = 'rounded-xl border border-gray-200 bg-white p-6 shadow-xs dark:border-white/10 dark:bg-white/5';
  $sidebarCardClass = 'rounded-xl border border-gray-200 bg-white p-4 shadow-xs dark:border-white/10 dark:bg-white/5';
@endphp

<div x-data="{ activeTab: '{{ $defaultLocale }}' }">
  <form action="{{ route('admin.groups.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
    @csrf

    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
      <div>
        <h1 class="text-xl font-semibold text-gray-900 dark:text-white">New Group</h1>
        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Create a new article group.</p>
      </div>
      <div class="flex items-center gap-3">
        <button type="submit" class="rounded-md bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow-xs hover:bg-indigo-500 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600 dark:bg-indigo-500 dark:shadow-none dark:focus-visible:outline-indigo-500">Save &amp; Publish</button>
      </div>
    </div>

    <div>
      <div class="grid grid-cols-1 sm:hidden">
        <select x-on:change="activeTab = $event.target.value" aria-label="Select a tab" class="col-start-1 row-start-1 w-full appearance-none rounded-md bg-white py-2 pr-8 pl-3 text-base text-gray-900 outline-1 -outline-offset-1 outline-gray-300 focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 dark:bg-white/5 dark:text-gray-100 dark:outline-white/10 dark:*:bg-gray-800 dark:focus:outline-indigo-500">
          @foreach ($locales as $locale)
            <option value="{{ $locale }}">{{ strtoupper($locale) }}</option>
          @endforeach
        </select>
        <svg viewBox="0 0 16 16" fill="currentColor" data-slot="icon" aria-hidden="true" class="pointer-events-none col-start-1 row-start-1 mr-2 size-5 self-center justify-self-end fill-gray-500 dark:fill-gray-400">
          <path d="M4.22 6.22a.75.75 0 0 1 1.06 0L8 8.94l2.72-2.72a.75.75 0 1 1 1.06 1.06l-3.25 3.25a.75.75 0 0 1-1.06 0L4.22 7.28a.75.75 0 0 1 0-1.06Z" clip-rule="evenodd" fill-rule="evenodd" />
        </svg>
      </div>
      <div class="hidden sm:block">
        <div class="border-b border-gray-200 dark:border-white/10">
          <nav aria-label="Tabs" class="-mb-px flex space-x-8">
            @foreach ($locales as $locale)
              <button
                type="button"
                x-on:click="activeTab = '{{ $locale }}'"
                :class="{ 'border-indigo-500 dark:border-indigo-400 text-indigo-600 dark:text-indigo-400': activeTab === '{{ $locale }}' }"
                class="border-b-2 border-transparent px-1 py-4 text-sm font-medium whitespace-nowrap text-gray-500 hover:border-gray-300 hover:text-gray-700 dark:text-gray-400 dark:hover:border-white/20 dark:hover:text-gray-200"
              >
                <span class="fi fi-{{ $locale === 'en' ? 'gb' : $locale }}"></span> {{ strtoupper($locale) }}
              </button>
            @endforeach
          </nav>
        </div>
      </div>
    </div>

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
      <div class="space-y-6 lg:col-span-2">
        @foreach ($locales as $locale)
          <div x-show="activeTab === '{{ $locale }}'" class="space-y-6">
            <section class="{{ $cardClass }}">
              <div class="mb-6">
                <h2 class="text-base font-semibold text-gray-900 dark:text-white">Group Information</h2>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Group content for {{ strtoupper($locale) }}.</p>
              </div>

              <div class="space-y-6">
                <div>
                  <label for="title_{{ $locale }}" class="{{ $labelClass }} admin-required">Title</label>
                  <div class="mt-2">
                    <input id="title_{{ $locale }}" type="text" name="title[{{ $locale }}]" value="{{ old('title.' . $locale, '') }}" autocomplete="given-title" class="{{ $inputClass }}" />
                  </div>
                  @if ($errors->has('title.*'))
                    <p class="{{ $errorClass }}">{{ $errors->first('title.*') }}</p>
                  @endif
                </div>

                <div>
                  <label for="description.{{ $locale }}" class="{{ $labelClass }} admin-required">Description</label>
                  <div class="mt-2">
                    <textarea id="description.{{ $locale }}" name="description[{{ $locale }}]" data-rich-counter="10000" rows="10" class="{{ $inputClass }}">{{ old('description.' . $locale, '') }}</textarea>
                  </div>
                  @if ($errors->has('description.*'))
                    <p class="{{ $errorClass }}">{{ $errors->first('description.*') }}</p>
                  @endif
                </div>

                <div>
                  <label for="caption_{{ $locale }}" class="{{ $labelClass }} admin-required">Caption</label>
                  <div class="mt-2">
                    <textarea id="caption_{{ $locale }}" name="caption[{{ $locale }}]" rows="3" class="{{ $inputClass }}">{{ old('caption.' . $locale, '') }}</textarea>
                  </div>
                  <p class="{{ $helpClass }}">Write a caption that will show on hover.</p>
                  @if ($errors->has('caption.*'))
                    <p class="{{ $errorClass }}">{{ $errors->first('caption.*') }}</p>
                  @endif
                </div>
              </div>
            </section>
          </div>
        @endforeach
      </div>

      <aside class="space-y-6 lg:sticky lg:top-20 lg:self-start">
        <section class="{{ $sidebarCardClass }}">
          <div class="flex items-center justify-between gap-4">
            <div>
              <h2 id="published-label" class="text-sm font-semibold text-gray-900 dark:text-white">Published</h2>
              <p id="published-description" class="mt-1 text-xs text-gray-500 dark:text-gray-400">Visible on the website.</p>
            </div>
            <div class="flex items-center gap-x-3">
              <div class="group relative inline-flex w-11 shrink-0 rounded-full bg-gray-200 p-0.5 inset-ring inset-ring-gray-900/5 outline-offset-2 outline-indigo-600 transition-colors duration-200 ease-in-out has-checked:bg-indigo-600 has-focus-visible:outline-2 dark:bg-white/5 dark:inset-ring-white/10 dark:outline-indigo-500 dark:has-checked:bg-indigo-500">
                <span class="size-5 rounded-full bg-white shadow-xs ring-1 ring-gray-900/5 transition-transform duration-200 ease-in-out group-has-checked:translate-x-5"></span>
                <input type="hidden" name="published" value="0">
                <input id="published" type="checkbox" name="published" value="1" checked aria-labelledby="published-label" class="absolute inset-0 appearance-none focus:outline-hidden"/>
              </div>
            </div>
          </div>
          @if ($errors->has('published'))
            <p class="{{ $errorClass }}">{{ $errors->first('published') }}</p>
          @endif
        </section>

        <section class="{{ $sidebarCardClass }}">
          <h2 class="text-sm font-semibold text-gray-900 dark:text-white">Details</h2>

          <div class="mt-4 space-y-4">
            <div>
              <label for="slug" class="{{ $labelClass }} admin-required">Slug</label>
              <div class="mt-2">
                <input id="slug" type="text" name="slug" value="{{ old('slug', '') }}" autocomplete="given-slug" class="{{ $inputClass }}" />
              </div>
              @if ($errors->has('slug'))
                <p class="{{ $errorClass }}">{{ $errors->first('slug') }}</p>
              @endif
            </div>
          </div>
        </section>

        <section class="{{ $sidebarCardClass }}">
          <h2 class="admin-required text-sm font-semibold text-gray-900 dark:text-white">Icon</h2>
          <div class="mt-4 flex items-center gap-x-3">
            <svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true" class="size-12 text-gray-300 dark:text-gray-500">
              <path d="M18.685 19.097A9.723 9.723 0 0 0 21.75 12c0-5.385-4.365-9.75-9.75-9.75S2.25 6.615 2.25 12a9.723 9.723 0 0 0 3.065 7.097A9.716 9.716 0 0 0 12 21.75a9.716 9.716 0 0 0 6.685-2.653Zm-12.54-1.285A7.486 7.486 0 0 1 12 15a7.486 7.486 0 0 1 5.855 2.812A8.224 8.224 0 0 1 12 20.25a8.224 8.224 0 0 1-5.855-2.438ZM15.75 9a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0Z" clip-rule="evenodd" fill-rule="evenodd" />
            </svg>
            <input id="icon" type="file" name="icon" class="sr-only" />
            <button id="changeIcon" type="button" class="rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-xs inset-ring inset-ring-gray-300 hover:bg-gray-50 dark:bg-white/10 dark:text-white dark:shadow-none dark:inset-ring-white/5 dark:hover:bg-white/20">Change</button>
          </div>
          <p id="iconName" class="mt-2 text-xs text-gray-500 dark:text-gray-400">No file selected</p>
          @if ($errors->has('icon'))
            <p class="{{ $errorClass }}">{{ $errors->first('icon') }}</p>
          @endif
        </section>
      </aside>
    </div>
  </form>
</div>

@endsection

@push('admin_script')
  <script type="text/javascript">
    document.addEventListener('DOMContentLoaded', function() {
      const locales = @json(adminium_locales());
      locales.forEach(locale => {
        CKEDITOR.replace('description.' + locale);
      });
    });
  </script>

  <script type="text/javascript">
    document.addEventListener('DOMContentLoaded', function() {
      const titleInput = document.getElementById('title_{{ $defaultLocale }}');
      const slugInput = document.getElementById('slug');

      if (!titleInput || !slugInput) {
        return;
      }

      titleInput.addEventListener('input', function() {
        const title = titleInput.value;
        const slug = generateSlug(title);
        slugInput.value = slug;
      });

      function generateSlug(text) {
        return text
          .toLowerCase()
          .trim()
          .replace(/[^a-z0-9\s-]/g, '')
          .replace(/\s+/g, '-')
          .replace(/-+/g, '-')
          .replace(/^-+|-+$/g, '');
      }
    });
  </script>

  <script type="text/javascript">
    document.addEventListener('DOMContentLoaded', function() {
      const changeButton = document.getElementById('changeIcon');
      const photoInput = document.getElementById('icon');
      const fileNameElement = document.getElementById('iconName');

      if (!changeButton || !photoInput || !fileNameElement) {
        return;
      }

      changeButton.addEventListener('click', () => photoInput.click());

      photoInput.addEventListener('change', () => {
        const file = photoInput.files[0];
        fileNameElement.textContent = file ? file.name : 'No file selected';
      });
    });
  </script>
@endpush
