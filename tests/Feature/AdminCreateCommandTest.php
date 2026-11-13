<?php

declare(strict_types=1);

use Calexandar\Adminium\Database\Seeders\PermissionSeeder;
use Calexandar\Adminium\Database\Seeders\RoleSeeder;
use Calexandar\Adminium\Database\Seeders\SuperAdminSeeder;
use Calexandar\Adminium\Tests\Fixtures\CustomAdminUser;
use Calexandar\Adminium\UserManagment\User;
use Calexandar\Adminium\UserManagment\UserPermissionName;
use Calexandar\Adminium\UserManagment\UserRoleName;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

beforeEach(function () {
    Role::findOrCreate('super_admin', adminium_permission_guard());
});

test('creates an administrator with a hashed password and role', function () {
    $this->artisan('adminium:admin:create', [
        '--name' => 'Site Admin',
        '--email' => 'ADMIN@example.com',
        '--password' => 'secret-password',
        '--role' => 'super_admin',
    ])->assertSuccessful();

    $user = User::query()->where('email', 'admin@example.com')->first();

    expect($user)->not->toBeNull()
        ->and(Hash::check('secret-password', $user->password))->toBeTrue()
        ->and($user->hasRole('super_admin'))->toBeTrue();
});

test('rejects an invalid email address', function () {
    $this->artisan('adminium:admin:create', [
        '--name' => 'Site Admin',
        '--email' => 'not-an-email',
        '--password' => 'secret-password',
    ])->assertFailed();

    expect(User::query()->count())->toBe(0);
});

test('rejects a short password', function () {
    $this->artisan('adminium:admin:create', [
        '--name' => 'Site Admin',
        '--email' => 'admin@example.com',
        '--password' => 'short',
    ])->assertFailed();

    expect(User::query()->count())->toBe(0);
});

test('rejects a duplicate email address', function () {
    User::query()->create([
        'name' => 'Existing User',
        'email' => 'admin@example.com',
        'password' => 'secret-password',
    ]);

    $this->artisan('adminium:admin:create', [
        '--name' => 'Site Admin',
        '--email' => 'admin@example.com',
        '--password' => 'secret-password',
    ])->assertFailed();

    expect(User::query()->count())->toBe(1);
});

test('rejects a role that has not been seeded', function () {
    $this->artisan('adminium:admin:create', [
        '--name' => 'Site Admin',
        '--email' => 'admin@example.com',
        '--password' => 'secret-password',
        '--role' => 'missing_role',
    ])->assertFailed();

    expect(User::query()->count())->toBe(0);
});

test('non-interactive execution fails when required values are missing', function () {
    $this->artisan('adminium:admin:create', ['--no-interaction' => true])
        ->assertFailed();

    expect(User::query()->count())->toBe(0);
});

test('role and permission seeders are idempotent', function () {
    $this->seed(RoleSeeder::class);
    $this->seed(RoleSeeder::class);
    $this->seed(PermissionSeeder::class);
    $this->seed(PermissionSeeder::class);

    expect(Role::query()->count())->toBe(count(UserRoleName::cases()))
        ->and(Permission::query()->count())->toBe(count(UserPermissionName::cases()));
});

test('the deprecated super admin seeder does not create an account', function () {
    $this->seed(SuperAdminSeeder::class);

    expect(User::query()->count())->toBe(0);
});

test('creates an administrator with a configured custom user model', function () {
    config()->set('adminium.user_model', CustomAdminUser::class);

    $this->artisan('adminium:admin:create', [
        '--name' => 'Custom Admin',
        '--email' => 'custom@example.com',
        '--password' => 'secret-password',
        '--role' => 'super_admin',
    ])->assertSuccessful();

    $user = CustomAdminUser::query()->where('email', 'custom@example.com')->first();

    expect($user)->toBeInstanceOf(CustomAdminUser::class)
        ->and($user->hasRole('super_admin'))->toBeTrue();
});
