@extends('dashboard::admin_layout')

@section('content')
<form action="{{ route('admin.users.update', $user) }}" method="POST" enctype="multipart/form-data">
  @csrf
  @method('PUT')
  <div class="space-y-12">
    <!-- Personal Information section -->
    <div class="grid grid-cols-1 gap-x-8 gap-y-10 border-b border-gray-900/10 pb-12 md:grid-cols-3 dark:border-white/10">
      <div>
        <h2 class="text-base/7 font-semibold text-gray-900 dark:text-white">Personal Information</h2>
        <p class="mt-1 text-sm/6 text-gray-600 dark:text-gray-400">Use a permanent address where you can receive mail.</p>
      </div>

      <div class="grid max-w-2xl grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6 md:col-span-2">
        <div class="sm:col-span-3">
          <label for="name" class="block text-sm/6 font-medium text-gray-900 dark:text-white">Name</label>
          <div class="mt-2">
            <input id="name" type="text" name="name" value="{{ $user->name }}" autocomplete="given-name" class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6 dark:bg-white/5 dark:text-white dark:outline-white/10 dark:placeholder:text-gray-500 dark:focus:outline-indigo-500" />
          </div>
          @error('name')
            <p id="name-error" class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
          @enderror
        </div>

        <div class="sm:col-span-4">
          <label for="title" class="block text-sm/6 font-medium text-gray-900 dark:text-white">Title</label>
          <div class="mt-2">
             <input id="title" type="text" name="title" value="{{ $user->title }}" autocomplete="given-name" class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6 dark:bg-white/5 dark:text-white dark:outline-white/10 dark:placeholder:text-gray-500 dark:focus:outline-indigo-500" />
          </div>
          @error('title')
            <p id="title-error" class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
          @enderror
        </div>

        <div class="sm:col-span-4">
          <label for="email" class="block text-sm/6 font-medium text-gray-900 dark:text-white">Email address</label>
          <div class="mt-2">
            <input id="email" type="email" name="email" value="{{ $user->email }}" autocomplete="email" class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6 dark:bg-white/5 dark:text-white dark:outline-white/10 dark:placeholder:text-gray-500 dark:focus:outline-indigo-500" />
          </div>
          @error('email')
            <p id="email-error" class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
          @enderror
        </div>

     
        <div class="sm:col-span-4">
          <label for="password" class="block text-sm/6 font-medium text-gray-900 dark:text-white">New password</label>
          <div class="mt-2">
            <input id="password" type="password" name="password" autocomplete="new-password" class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6 dark:bg-white/5 dark:text-white dark:outline-white/10 dark:placeholder:text-gray-500 dark:focus:outline-indigo-500" />
          </div>
          <p class="mt-1 text-sm/6 text-gray-600 dark:text-gray-400">Leave blank to keep the current password.</p>
          @error('password')
            <p id="password-error" class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
          @enderror
        </div>

        <div class="sm:col-span-4">
          <label for="password_confirmation" class="block text-sm/6 font-medium text-gray-900 dark:text-white">Confirm new password</label>
          <div class="mt-2">
            <input id="password_confirmation" type="password" name="password_confirmation" autocomplete="new-password" class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6 dark:bg-white/5 dark:text-white dark:outline-white/10 dark:placeholder:text-gray-500 dark:focus:outline-indigo-500" />
          </div>
        </div>

        <div class="col-span-full">
          <label for="avatar" class="block text-sm/6 font-medium text-gray-900 dark:text-white">Avatar</label>
          <div class="mt-2 flex items-center gap-x-3">
            @if (method_exists($user, 'getMedia') && $user->getMedia('avatars')->count())
                <img src="{{ $user->getFirstMediaUrl('avatars') }}" alt="" class="size-12 text-gray-300 dark:text-gray-500" />
            @else
              <svg viewBox="0 0 24 24" fill="currentColor" data-slot="avatar" aria-hidden="true" class="size-12 text-gray-300 dark:text-gray-500">
                <path d="M18.685 19.097A9.723 9.723 0 0 0 21.75 12c0-5.385-4.365-9.75-9.75-9.75S2.25 6.615 2.25 12a9.723 9.723 0 0 0 3.065 7.097A9.716 9.716 0 0 0 12 21.75a9.716 9.716 0 0 0 6.685-2.653Zm-12.54-1.285A7.486 7.486 0 0 1 12 15a7.486 7.486 0 0 1 5.855 2.812A8.224 8.224 0 0 1 12 20.25a8.224 8.224 0 0 1-5.855-2.438ZM15.75 9a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0Z" clip-rule="evenodd" fill-rule="evenodd" />
              </svg>
            @endif  
            <input id="avatar" type="file" name="avatar" class="sr-only" />
            <button id="changeAvatar"  type="button" class="rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-xs inset-ring inset-ring-gray-300 hover:bg-gray-50 dark:bg-white/10 dark:text-white dark:shadow-none dark:inset-ring-white/5 dark:hover:bg-white/20">Change</button>
            <p id="avatarName" class="text-gray-500 dark:text-gray-400">No file selected</p>
          </div>
          @error('avatar')
            <p id="avatar-error" class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
          @enderror
        </div>

      </div>
    </div>

    <div class="grid grid-cols-1 gap-x-8 gap-y-10 border-b border-gray-900/10 pb-12 md:grid-cols-3 dark:border-white/10">
      <div>
        <h2 class="text-base/7 font-semibold text-gray-900 dark:text-white">Permissions</h2>
        <p class="mt-1 text-sm/6 text-gray-600 dark:text-gray-400">Select all the permissions you want to give to this user.</p>
      </div>

      <div class="max-w-2xl space-y-10 md:col-span-2">
        <fieldset>
          <legend class="text-sm/6 font-semibold text-gray-900 dark:text-white">Admin permissions.</legend>
          <p class="mt-1 text-sm/6 text-gray-600 dark:text-gray-400">These are permissions that are only available to you.</p>
          <div class="mt-6 grid grid-cols-1  md:grid-cols-2 gap-4">
            @foreach (\Calexandar\Adminium\UserManagment\UserPermissionName::cases() as $permission)
              
              <div class="flex items-center gap-x-3">               
                <input id="{{ $permission }}" type="checkbox" name="permissions[]" @if(in_array($permission->value, $user->getAllPermissions()->pluck('name')->toArray())) checked @endif value="{{ $permission }}" class="relative size-4 appearance-none rounded-full border border-gray-300 bg-white before:absolute before:inset-1 before:rounded-full before:bg-white not-checked:before:hidden checked:border-indigo-600 checked:bg-indigo-600 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600 disabled:border-gray-300 disabled:bg-gray-100 disabled:before:bg-gray-400 dark:border-white/10 dark:bg-white/5 dark:checked:border-indigo-500 dark:checked:bg-indigo-500 dark:focus-visible:outline-indigo-500 dark:disabled:border-white/5 dark:disabled:bg-white/10 dark:disabled:before:bg-white/20 forced-colors:appearance-auto forced-colors:before:hidden" />
                <label for="{{ $permission }}" class="block text-sm/6 font-medium text-gray-900 dark:text-white">{{ $permission->label() }}</label>
              </div>
              
            @endforeach
          @error('permissions')
            <p id="permissions-error" class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
          @enderror
          </div>
        </fieldset>
      </div>
    </div>
  </div>

  <div class="mt-6 flex items-center justify-end gap-x-6">
    <button type="button" class="text-sm/6 font-semibold text-gray-900 dark:text-white">Cancel</button>
    <button type="submit" class="rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-xs hover:bg-indigo-500 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600 dark:bg-indigo-500 dark:shadow-none dark:focus-visible:outline-indigo-500">Save</button>
  </div>
</form>


@endsection

@push('admin_script')
  <script>
      document.addEventListener('DOMContentLoaded', () => {
          const changeButton = document.getElementById('changeAvatar');
          const photoInput = document.getElementById('avatar');
          const fileNameElement = document.getElementById('avatarName');

         changeButton.addEventListener('click', () => {
          photoInput.click();
      });

      photoInput.addEventListener('change', () => {
          const file = photoInput.files[0];
          if (file) {
              fileNameElement.textContent = file.name;
          } else {
              fileNameElement.textContent = 'No file selected';
          }
      });
  });
  </script>
@endpush