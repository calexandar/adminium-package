<?php

declare(strict_types=1);

use Calexandar\Adminium\UserManagment\User;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

use function Pest\Laravel\actingAs;

function usersControllerAdmin(): User
{
    Role::findOrCreate('super_admin', adminium_permission_guard());
    Role::findOrCreate('admin', adminium_permission_guard());

    $user = User::query()->create([
        'name' => 'Super Admin',
        'email' => 'super-'.uniqid().'@example.com',
        'password' => 'password',
    ]);
    $user->assignRole('super_admin');

    return $user;
}

test('an administrator can be created without an avatar', function () {
    $permission = Permission::findOrCreate('manage_pages', adminium_permission_guard());
    $admin = usersControllerAdmin();

    actingAs($admin, adminium_guard())
        ->post(route('admin.users.store'), [
            'name' => 'New Admin',
            'title' => 'Editor',
            'email' => 'new-admin@example.com',
            'password' => 'new-password',
            'password_confirmation' => 'new-password',
            'permissions' => [$permission->name],
        ])
        ->assertRedirect(route('admin.users.index'));

    $user = User::query()->where('email', 'new-admin@example.com')->first();

    expect($user)->not->toBeNull()
        ->and(Hash::check('new-password', $user->password))->toBeTrue()
        ->and($user->hasRole('admin'))->toBeTrue()
        ->and($user->hasPermissionTo($permission->name))->toBeTrue();
});

test('an administrator can be updated without an avatar and change their password', function () {
    $permission = Permission::findOrCreate('manage_pages', adminium_permission_guard());
    $admin = usersControllerAdmin();

    $user = User::query()->create([
        'name' => 'Existing Admin',
        'title' => 'Editor',
        'email' => 'existing@example.com',
        'password' => 'old-password',
    ]);

    actingAs($admin, adminium_guard())
        ->put(route('admin.users.update', $user), [
            'name' => 'Updated Admin',
            'title' => 'Senior Editor',
            'email' => 'updated@example.com',
            'password' => 'new-password',
            'password_confirmation' => 'new-password',
            'permissions' => [$permission->name],
        ])
        ->assertRedirect(route('admin.users.index'));

    $user->refresh();

    expect($user->name)->toBe('Updated Admin')
        ->and($user->email)->toBe('updated@example.com')
        ->and(Hash::check('new-password', $user->password))->toBeTrue();
});

test('an administrator can be updated without changing their password', function () {
    $permission = Permission::findOrCreate('manage_pages', adminium_permission_guard());
    $admin = usersControllerAdmin();

    $user = User::query()->create([
        'name' => 'Existing Admin',
        'title' => 'Editor',
        'email' => 'existing@example.com',
        'password' => 'old-password',
    ]);

    actingAs($admin, adminium_guard())
        ->put(route('admin.users.update', $user), [
            'name' => 'Updated Admin',
            'title' => 'Senior Editor',
            'email' => 'updated@example.com',
            'permissions' => [$permission->name],
        ])
        ->assertRedirect(route('admin.users.index'));

    $user->refresh();

    expect(Hash::check('old-password', $user->password))->toBeTrue();
});

test('the user edit form renders without an avatar', function () {
    $admin = usersControllerAdmin();

    $user = User::query()->create([
        'name' => 'Existing Admin',
        'title' => 'Editor',
        'email' => 'existing@example.com',
        'password' => 'old-password',
    ]);

    actingAs($admin, adminium_guard())
        ->get(route('admin.users.edit', $user))
        ->assertSuccessful();
});
