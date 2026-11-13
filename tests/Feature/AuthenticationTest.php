<?php

declare(strict_types=1);

use Calexandar\Adminium\UserManagment\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\post;

function authenticationUser(string $email = 'admin@example.com'): User
{
    return User::query()->create([
        'name' => 'Admin User',
        'email' => $email,
        'password' => 'password',
    ]);
}

beforeEach(function () {
    Role::findOrCreate('super_admin', adminium_permission_guard());
});

test('adminium registers a dedicated guard without replacing the host guard', function () {
    expect(config('auth.guards.'.adminium_guard()))->toMatchArray([
        'driver' => 'session',
        'provider' => adminium_auth_provider(),
    ])
        ->and(config('auth.providers.'.adminium_auth_provider().'.model'))->toBe(adminium_user_model())
        ->and(config('auth.guards.web.provider'))->not->toBe(adminium_auth_provider());
});

test('every admin route except sign in requires authentication', function () {
    $routes = collect(Route::getRoutes()->getRoutes())
        ->filter(fn ($route): bool => str_starts_with((string) $route->getName(), 'admin.'));

    expect($routes)->not->toBeEmpty();

    foreach ($routes as $route) {
        if (in_array($route->getName(), ['admin.sign-in', 'admin.sign-in.view'], true)) {
            continue;
        }

        expect($route->gatherMiddleware())->toContain('adminium.auth');
    }
});

test('guests are redirected to the admin sign in page', function () {
    $this->get(route('admin.dashboard'))
        ->assertRedirect(route('admin.sign-in.view'));
});

test('a host user authenticated on the web guard cannot access adminium', function () {
    $user = authenticationUser();

    actingAs($user, 'web')
        ->get(route('admin.dashboard'))
        ->assertRedirect(route('admin.sign-in.view'));
});

test('an administrator can sign in and is authenticated on the adminium guard', function () {
    $user = authenticationUser();
    $user->assignRole('super_admin');

    post(route('admin.sign-in'), [
        'email' => 'ADMIN@example.com',
        'password' => 'password',
        'remember-me' => '1',
    ])->assertRedirect(route('admin.dashboard'));

    expect(Auth::guard(adminium_guard())->check())->toBeTrue()
        ->and(Auth::guard('web')->check())->toBeFalse()
        ->and(Auth::guard(adminium_guard())->user()?->is($user))->toBeTrue();
});

test('legacy passwords below the current creation policy can still sign in', function () {
    $user = authenticationUser();
    $user->setAttribute('password', 'short');
    $user->save();
    $user->assignRole('super_admin');

    post(route('admin.sign-in'), [
        'email' => 'admin@example.com',
        'password' => 'short',
    ])->assertRedirect(route('admin.dashboard'));

    expect(Auth::guard(adminium_guard())->check())->toBeTrue();
});

test('invalid credentials are rejected', function () {
    authenticationUser();

    post(route('admin.sign-in'), [
        'email' => 'admin@example.com',
        'password' => 'wrong-password',
    ])->assertSessionHasErrors('email');

    expect(Auth::guard(adminium_guard())->check())->toBeFalse();
});

test('authenticated administrators are redirected away from the sign in page', function () {
    $user = authenticationUser();
    $user->assignRole('super_admin');

    actingAs($user, adminium_guard())
        ->get(route('admin.sign-in.view'))
        ->assertRedirect(route('admin.dashboard'));
});

test('super administrators can access protected modules', function () {
    $user = authenticationUser();
    $user->assignRole('super_admin');

    actingAs($user, adminium_guard())
        ->get(route('admin.pages.index'))
        ->assertSuccessful();
});

test('users without a module permission are forbidden', function (string $module) {
    $user = authenticationUser();

    actingAs($user, adminium_guard())
        ->get(route("admin.{$module}.index"))
        ->assertForbidden();
})->with([
    'users',
    'categories',
    'products',
    'articles',
    'groups',
    'news',
    'media',
    'pages',
]);

test('users without the settings permission are forbidden', function () {
    $user = authenticationUser();

    actingAs($user, adminium_guard())
        ->get(route('admin.settings.index', ['group' => 'general']))
        ->assertForbidden();
});

test('users with a module permission can access the module', function () {
    $role = Role::findOrCreate('admin', adminium_permission_guard());
    $permission = Permission::findOrCreate('manage_pages', adminium_permission_guard());
    $role->givePermissionTo($permission);

    $user = authenticationUser();
    $user->assignRole($role);

    actingAs($user, adminium_guard())
        ->get(route('admin.pages.index'))
        ->assertSuccessful();
});

test('logging out clears the adminium guard and redirects to sign in', function () {
    $user = authenticationUser();
    $user->assignRole('super_admin');

    actingAs($user, adminium_guard())
        ->post(route('admin.logout'))
        ->assertRedirect(route('admin.sign-in.view'));

    expect(Auth::guard(adminium_guard())->check())->toBeFalse();
});
