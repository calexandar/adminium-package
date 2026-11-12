@php
    $article = $article ?? null;
    $locales = adminium_locales();
    $defaultLocale = $locales[0] ?? 'en';
    $isEdit = $article !== null;
    $action = $action ?? route('admin.articles.store');
    $method = $method ?? 'POST';

    $pageTitle = $isEdit
        ? ($article->getTranslation('title', $defaultLocale) ?: 'Edit Article')
        : 'New Article';

    $published = old('published', $isEdit ? $article->published : true);
    $coverImage = $isEdit ? $article->getFirstMediaUrl('articles') : '';

    $inputClass = 'block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6 dark:bg-white/5 dark:text-white dark:outline-white/10 dark:placeholder:text-gray-500 dark:focus:outline-indigo-500';
    $selectClass = 'col-start-1 row-start-1 w-full appearance-none rounded-md bg-white py-1.5 pr-8 pl-3 text-base text-gray-900 outline-1 -outline-offset-1 outline-gray-300 focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6 dark:bg-white/5 dark:text-white dark:outline-white/10 dark:*:bg-gray-800 dark:focus:outline-indigo-500';
    $labelClass = 'block text-sm/6 font-medium text-gray-900 dark:text-white';
    $helpClass = 'mt-2 text-sm text-gray-500 dark:text-gray-400';
    $errorClass = 'mt-2 text-sm text-red-600 dark:text-red-400';
    $cardClass = 'rounded-xl border border-gray-200 bg-white p-6 shadow-xs dark:border-white/10 dark:bg-white/5';
    $sidebarCardClass = 'rounded-xl border border-gray-200 bg-white p-4 shadow-xs dark:border-white/10 dark:bg-white/5';
@endphp

<div x-data="{ activeTab: '{{ $defaultLocale }}' }">
  <form action="{{ $action }}" method="POST" enctype="multipart/form-data" class="space-y-6">
    @csrf
    @if ($method !== 'POST')
      @method($method)
    @endif

    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
      <div>
        <h1 class="text-xl font-semibold text-gray-900 dark:text-white">{{ $pageTitle }}</h1>
        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">{{ $isEdit ? 'Update article content and settings.' : 'Create and publish a new article.' }}</p>
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
          @php
              $titleValue = old('title.' . $locale, $isEdit ? $article->getTranslation('title', $locale) : '');
              $contentValue = old('content.' . $locale, $isEdit ? $article->getTranslation('content', $locale) : '');
              $shortDescriptionValue = old('short_description.' . $locale, $isEdit ? $article->getTranslation('short_description', $locale) : '');
          @endphp
          <div x-show="activeTab === '{{ $locale }}'" class="space-y-6">
            <section class="{{ $cardClass }}">
              <div class="mb-6">
                <h2 class="text-base font-semibold text-gray-900 dark:text-white">Main Content</h2>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Article content for {{ strtoupper($locale) }}.</p>
              </div>

              <div class="space-y-6">
                <div>
                  <label for="title_{{ $locale }}" class="{{ $labelClass }} admin-required">Title</label>
                  <div class="mt-2">
                    <input id="title_{{ $locale }}" type="text" name="title[{{ $locale }}]" value="{{ $titleValue }}" autocomplete="given-title" class="{{ $inputClass }}" />
                  </div>
                  @error('title.' . $locale)
                    <p class="{{ $errorClass }}">{{ $message }}</p>
                  @enderror
                </div>

                <div>
                  <label for="content_{{ $locale }}" class="{{ $labelClass }} admin-required">Content</label>
                  <div class="mt-2">
                    <textarea id="content_{{ $locale }}" name="content[{{ $locale }}]" data-rich-counter="10000" rows="12" class="{{ $inputClass }}">{{ $contentValue }}</textarea>
                  </div>
                  @error('content.' . $locale)
                    <p class="{{ $errorClass }}">{{ $message }}</p>
                  @enderror
                </div>

                <div>
                  <label for="short_description_{{ $locale }}" class="{{ $labelClass }} admin-required">Short Description</label>
                  <div class="mt-2">
                    <textarea id="short_description_{{ $locale }}" name="short_description[{{ $locale }}]" rows="3" class="{{ $inputClass }}">{{ $shortDescriptionValue }}</textarea>
                  </div>
                  <p class="{{ $helpClass }}">Write a short description that will show on the homepage.</p>
                  @error('short_description.' . $locale)
                    <p class="{{ $errorClass }}">{{ $message }}</p>
                  @enderror
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
                <input id="published" type="checkbox" name="published" value="1" @checked($published) aria-labelledby="published-label" class="absolute inset-0 appearance-none focus:outline-hidden" />
              </div>
            </div>
          </div>
          @error('published')
            <p class="{{ $errorClass }}">{{ $message }}</p>
          @enderror
        </section>

        <section class="{{ $sidebarCardClass }}">
          <h2 class="text-sm font-semibold text-gray-900 dark:text-white">Details</h2>

          <div class="mt-4 space-y-4">
            <div>
              <label for="group" class="{{ $labelClass }} admin-required">Group</label>
              <div class="mt-2 grid grid-cols-1">
                <select id="group" name="group_id" autocomplete="group-name" class="{{ $selectClass }}">
                  <option value="">Select a group</option>
                  @foreach ($groups as $group)
                    <option value="{{ $group->id }}" @selected(old('group_id', $isEdit ? $article->group_id : '') == $group->id)>{{ $group->title }}</option>
                  @endforeach
                </select>
                <svg viewBox="0 0 16 16" fill="currentColor" data-slot="icon" aria-hidden="true" class="pointer-events-none col-start-1 row-start-1 mr-2 size-5 self-center justify-self-end text-gray-500 sm:size-4 dark:text-gray-400">
                  <path d="M4.22 6.22a.75.75 0 0 1 1.06 0L8 8.94l2.72-2.72a.75.75 0 1 1 1.06 1.06l-3.25 3.25a.75.75 0 0 1-1.06 0L4.22 7.28a.75.75 0 0 1 0-1.06Z" clip-rule="evenodd" fill-rule="evenodd" />
                </svg>
              </div>
              @error('group_id')
                <p class="{{ $errorClass }}">{{ $message }}</p>
              @enderror
            </div>

            <div>
              <label for="author" class="{{ $labelClass }} admin-required">Author</label>
              <div class="mt-2 grid grid-cols-1">
                <select id="author" name="author_id" autocomplete="author-name" class="{{ $selectClass }}">
                  <option value="">Select an author</option>
                  @foreach ($users as $author)
                    <option value="{{ $author->id }}" @selected(old('author_id', $isEdit ? $article->author_id : '') == $author->id)>{{ $author->name }}</option>
                  @endforeach
                </select>
                <svg viewBox="0 0 16 16" fill="currentColor" data-slot="icon" aria-hidden="true" class="pointer-events-none col-start-1 row-start-1 mr-2 size-5 self-center justify-self-end text-gray-500 sm:size-4 dark:text-gray-400">
                  <path d="M4.22 6.22a.75.75 0 0 1 1.06 0L8 8.94l2.72-2.72a.75.75 0 1 1 1.06 1.06l-3.25 3.25a.75.75 0 0 1-1.06 0L4.22 7.28a.75.75 0 0 1 0-1.06Z" clip-rule="evenodd" fill-rule="evenodd" />
                </svg>
              </div>
              @error('author_id')
                <p class="{{ $errorClass }}">{{ $message }}</p>
              @enderror
            </div>

            <div>
              <label for="slug" class="{{ $labelClass }} admin-required">Slug</label>
              <div class="mt-2">
                <input id="slug" type="text" name="slug" value="{{ old('slug', $isEdit ? $article->slug : '') }}" autocomplete="given-slug" class="{{ $inputClass }}" />
              </div>
              @error('slug')
                <p class="{{ $errorClass }}">{{ $message }}</p>
              @enderror
            </div>
          </div>
        </section>

        <section class="{{ $sidebarCardClass }}">
          <div class="flex items-center justify-between gap-4">
            <h2 class="text-sm font-semibold text-gray-900 dark:text-white">Cover image</h2>
            <span class="text-xs text-gray-500 dark:text-gray-400">{{ $isEdit ? 'Optional' : '' }}</span>
          </div>
          <div class="mt-4 flex justify-center rounded-lg border border-dashed border-gray-900/25 px-4 py-6 dark:border-white/25">
            <div class="text-center">
              @if ($coverImage)
                <img id="preview" src="{{ $coverImage }}" alt="" class="mx-auto mb-4 max-h-40 rounded-md object-contain" />
              @else
                <img id="preview" class="mx-auto mb-4 hidden max-h-40 rounded-md object-contain" />
                <svg id="upload-icon" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true" class="mx-auto size-10 text-gray-300 dark:text-gray-600">
                  <path d="M1.5 6a2.25 2.25 0 0 1 2.25-2.25h16.5A2.25 2.25 0 0 1 22.5 6v12a2.25 2.25 0 0 1-2.25 2.25H3.75A2.25 2.25 0 0 1 1.5 18V6ZM3 16.06V18c0 .414.336.75.75.75h16.5A.75.75 0 0 0 21 18v-1.94l-2.69-2.689a1.5 1.5 0 0 0-2.12 0l-.88.879.97.97a.75.75 0 1 1-1.06 1.06l-5.16-5.159a1.5 1.5 0 0 0-2.12 0L3 16.061Zm10.125-7.81a1.125 1.125 0 1 1 2.25 0 1.125 1.125 0 0 1-2.25 0Z" fill-rule="evenodd" clip-rule="evenodd" />
                </svg>
              @endif
              <div class="mt-3 flex justify-center text-sm/6 text-gray-600 dark:text-gray-400">
                <label for="cover_image" class="relative cursor-pointer rounded-md bg-transparent font-semibold text-indigo-600 hover:text-indigo-500 focus-within:outline-2 focus-within:outline-offset-2 focus-within:outline-indigo-600 dark:text-indigo-400 dark:hover:text-indigo-300 dark:focus-within:outline-indigo-500">
                  <span>Upload a file</span>
                  <input id="cover_image" type="file" name="cover_image" accept="image/*" class="sr-only" onchange="previewFile(event)" />
                </label>
              </div>
              <p class="mt-1 text-xs/5 text-gray-500 dark:text-gray-400">PNG, JPG, GIF, WEBP, AVIF up to 10MB</p>
            </div>
          </div>
          @error('cover_image')
            <p class="{{ $errorClass }}">{{ $message }}</p>
          @enderror
        </section>

        <section class="{{ $sidebarCardClass }}">
          <div class="flex items-center justify-between gap-4">
            <h2 class="text-sm font-semibold text-gray-900 dark:text-white">SEO</h2>
            <span class="text-xs text-gray-500 dark:text-gray-400" x-text="activeTab.toUpperCase()"></span>
          </div>

          @foreach ($locales as $locale)
            @php
                $metaTitleValue = old('meta_title.' . $locale, $isEdit ? $article->getTranslation('meta_title', $locale) : '');
                $metaDescriptionValue = old('meta_description.' . $locale, $isEdit ? $article->getTranslation('meta_description', $locale) : '');
            @endphp
            <div x-show="activeTab === '{{ $locale }}'" class="mt-4 space-y-4">
              <div>
                <label for="meta_title_{{ $locale }}" class="{{ $labelClass }} admin-required">Meta title</label>
                <div class="mt-2">
                  <input id="meta_title_{{ $locale }}" type="text" name="meta_title[{{ $locale }}]" data-char-counter="60" maxlength="60" value="{{ $metaTitleValue }}" autocomplete="given-title" class="{{ $inputClass }}" />
                </div>
                @error('meta_title.' . $locale)
                  <p class="{{ $errorClass }}">{{ $message }}</p>
                @enderror
              </div>

              <div>
                <label for="meta_description_{{ $locale }}" class="{{ $labelClass }} admin-required">Meta description</label>
                <div class="mt-2">
                  <textarea id="meta_description_{{ $locale }}" name="meta_description[{{ $locale }}]" data-char-counter="160" maxlength="160" rows="3" class="{{ $inputClass }}">{{ $metaDescriptionValue }}</textarea>
                </div>
                @error('meta_description.' . $locale)
                  <p class="{{ $errorClass }}">{{ $message }}</p>
                @enderror
              </div>
            </div>
          @endforeach
        </section>
      </aside>
    </div>

  </form>
</div>

@push('admin_script')
  <script type="text/javascript">
    document.addEventListener('DOMContentLoaded', function() {
      const locales = @json($locales);
      locales.forEach(locale => {
        CKEDITOR.replace( 'content_'+locale );
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
    function previewFile(event) {
      const file = event.target.files[0];
      if (!file) return;

      const preview = document.getElementById('preview');
      const icon = document.getElementById('upload-icon');

      if (preview) {
        preview.src = URL.createObjectURL(file);
        preview.classList.remove('hidden');
      }

      if (icon) {
        icon.classList.add('hidden');
      }
    }
  </script>
@endpush