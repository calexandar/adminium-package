<?php

declare(strict_types=1);

namespace Calexandar\Adminium\Users;

use Calexandar\Adminium\UserManagment\UserRoleName;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

final readonly class UsersController
{
    public function index(): View
    {
        $model = adminium_user_model();
        $users = $model::query()->with('roles')->get();

        return view('users::index', compact('users'));
    }

    public function create(): View
    {
        return view('users::create');
    }

    public function store(CreateUserRequest $request): RedirectResponse
    {
        $model = adminium_user_model();

        $user = $model::query()->create([
            'name' => $request->string('name')->value(),
            'title' => $request->string('title')->value(),
            'email' => $request->string('email')->value(),
            'password' => Hash::make($request->string('password')->value()),
        ]);

        if ($request->hasFile('avatar') && method_exists($user, 'addMediaFromRequest')) {
            $user->addMediaFromRequest('avatar')->toMediaCollection('avatars');
        }

        $selectedPermissions = $request->input('permissions', []);

        if (is_array($selectedPermissions) && method_exists($user, 'syncPermissions')) {
            call_user_func([$user, 'syncPermissions'], $selectedPermissions);
        }

        if (method_exists($user, 'assignRole')) {
            call_user_func([$user, 'assignRole'], UserRoleName::ADMIN->value);
        }

        return redirect()->route('admin.users.index')->with('success', 'User created successfully');
    }

    public function edit(string $user): View
    {
        $user = $this->findUser($user);

        return view('users::edit', compact('user'));
    }

    public function update(UpdateUserRequest $request, string $user): RedirectResponse
    {
        $user = $this->findUser($user);

        $user->fill($request->safe()->only(['name', 'title', 'email']));

        if ($request->filled('password')) {
            $user->setAttribute('password', Hash::make($request->string('password')->value()));
        }

        $user->save();

        if ($request->hasFile('avatar') && method_exists($user, 'addMediaFromRequest')) {
            if (method_exists($user, 'clearMediaCollection')) {
                $user->clearMediaCollection('avatars');
            }

            $user->addMediaFromRequest('avatar')->toMediaCollection('avatars');
        }

        $selectedPermissions = $request->input('permissions', []);

        if (is_array($selectedPermissions) && method_exists($user, 'syncPermissions')) {
            call_user_func([$user, 'syncPermissions'], $selectedPermissions);
        }

        return redirect()->route('admin.users.index')->with('success', 'User updated successfully');
    }

    public function destroy(string $user): RedirectResponse
    {
        $this->findUser($user)->delete();

        return redirect()->route('admin.users.index')->with('success', 'User deleted successfully');
    }

    private function findUser(string $id): Model
    {
        $model = adminium_user_model();

        return $model::query()->findOrFail($id);
    }
}
