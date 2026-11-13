<?php

declare(strict_types=1);

use Calexandar\Adminium\Tests\CustomUserTestCase;
use Calexandar\Adminium\Tests\Fixtures\CustomAdminUser;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\post;

uses(CustomUserTestCase::class, RefreshDatabase::class);

beforeEach(function () {
    Role::findOrCreate('super_admin', adminium_permission_guard());
});

test('adminium uses the configured custom user model for authentication', function () {
    expect(adminium_user_model())->toBe(CustomAdminUser::class)
        ->and(config('auth.providers.'.adminium_auth_provider().'.model'))->toBe(CustomAdminUser::class)
        ->and($this->app['auth']->createUserProvider(adminium_auth_provider())?->getModel())->toBe(CustomAdminUser::class);
});

test('an administrator using the custom user model can sign in', function () {
    $user = CustomAdminUser::query()->create([
        'name' => 'Custom Admin',
        'email' => 'custom-admin@example.com',
        'password' => 'password',
    ]);
    $user->assignRole('super_admin');

    post(route('admin.sign-in'), [
        'email' => 'custom-admin@example.com',
        'password' => 'password',
    ])->assertRedirect(route('admin.dashboard'));

    expect(Auth::guard(adminium_guard())->user()?->is($user))->toBeTrue();
});

test('the custom user model can access authorized admin routes', function () {
    $user = CustomAdminUser::query()->create([
        'name' => 'Custom Admin',
        'email' => 'custom-admin@example.com',
        'password' => 'password',
    ]);
    $user->assignRole('super_admin');

    actingAs($user, adminium_guard())
        ->get(route('admin.pages.index'))
        ->assertSuccessful();
});
