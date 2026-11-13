<?php

declare(strict_types=1);

use Calexandar\Adminium\Database\Seeders\PermissionSeeder;
use Calexandar\Adminium\Navigation\NavigationRegistry;
use Calexandar\Adminium\Permissions\PermissionDefinition;
use Calexandar\Adminium\Permissions\PermissionRegistry;
use Calexandar\Adminium\Tests\ModuleTestCase;
use Calexandar\Adminium\UserManagment\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

use function Pest\Laravel\actingAs;

uses(ModuleTestCase::class, RefreshDatabase::class);

function reportsModuleAdmin(): User
{
    Role::findOrCreate('super_admin', adminium_permission_guard());

    $user = User::query()->create([
        'name' => 'Reports Admin',
        'email' => 'reports-'.uniqid().'@example.com',
        'password' => 'password',
    ]);
    $user->assignRole('super_admin');

    return $user;
}

test('custom modules can register navigation and permissions', function () {
    $navigation = app(NavigationRegistry::class);
    $item = $navigation->get('reports');
    $permissions = app(PermissionRegistry::class);

    expect($navigation->has('reports'))->toBeTrue()
        ->and($item)->not->toBeNull()
        ->and($item->label)->toBe('Reports')
        ->and($item->route)->toBe('admin.reports-test')
        ->and($item->permission)->toBe('view_reports')
        ->and($item->group)->toBe('reports')
        ->and($item->url())->toBe(route('admin.reports-test'))
        ->and($permissions->has('view_reports'))->toBeTrue()
        ->and($permissions->get('view_reports')?->module)->toBe('reports');
});

test('navigation groups include custom and first-party items in order', function () {
    $groups = collect(app(NavigationRegistry::class)->grouped());

    $reports = $groups->first(fn (array $group): bool => $group['group']->id === 'reports');

    expect($reports)->not->toBeNull()
        ->and($reports['group']->label)->toBe('Reports')
        ->and(array_column($reports['items'], 'id'))->toBe(['reports'])
        ->and($groups->first()['group']->id)->toBe('main');
});

test('registering a permission twice does not duplicate it', function () {
    $permissions = app(PermissionRegistry::class);
    $definition = new PermissionDefinition('view_reports', 'View reports', 'reports');

    $permissions->register($definition);
    $permissions->register($definition);

    $matches = array_filter(
        $permissions->all(),
        static fn (PermissionDefinition $permission): bool => $permission->name === 'view_reports',
    );

    expect($matches)->toHaveCount(1);
});

test('navigation items can be removed by their identifier', function () {
    $navigation = app(NavigationRegistry::class);

    $navigation->remove('reports');

    expect($navigation->has('reports'))->toBeFalse()
        ->and($navigation->get('reports'))->toBeNull();
});

test('the permission seeder does not delete application permissions', function () {
    $applicationPermission = Permission::findOrCreate('application_permission', adminium_permission_guard());

    $this->seed(PermissionSeeder::class);

    expect(Permission::query()
        ->where('name', $applicationPermission->name)
        ->where('guard_name', adminium_permission_guard())
        ->exists())->toBeTrue();
});

test('the permission seeder includes custom module permissions', function () {
    $this->seed(PermissionSeeder::class);

    expect(Permission::query()
        ->where('name', 'view_reports')
        ->where('guard_name', adminium_permission_guard())
        ->exists())->toBeTrue()
        ->and(Permission::query()
            ->where('name', 'manage_news')
            ->where('guard_name', adminium_permission_guard())
            ->exists())->toBeTrue();
});

test('custom module routes are guarded by registered permissions', function () {
    $admin = reportsModuleAdmin();

    actingAs($admin, adminium_guard())
        ->get(route('admin.reports-test'))
        ->assertSuccessful()
        ->assertSee('Reports');

    Permission::findOrCreate('view_reports', adminium_permission_guard());

    $editor = User::query()->create([
        'name' => 'Reports Editor',
        'email' => 'reports-editor-'.uniqid().'@example.com',
        'password' => 'password',
    ]);
    $editor->givePermissionTo('view_reports');

    actingAs($editor, adminium_guard())
        ->get(route('admin.reports-test'))
        ->assertSuccessful();
});

test('custom module routes reject users without the registered permission', function () {
    $user = User::query()->create([
        'name' => 'No Reports Access',
        'email' => 'no-reports-'.uniqid().'@example.com',
        'password' => 'password',
    ]);

    actingAs($user, adminium_guard())
        ->get(route('admin.reports-test'))
        ->assertForbidden();
});

test('custom navigation items render in the admin layout', function () {
    actingAs(reportsModuleAdmin(), adminium_guard())
        ->get(route('admin.pages.index'))
        ->assertSuccessful()
        ->assertSee('Reports')
        ->assertSee('Dashboard');
});

test('navigation items are not rendered without their permission', function () {
    Permission::findOrCreate('manage_pages', adminium_permission_guard());

    $editor = User::query()->create([
        'name' => 'Pages Editor',
        'email' => 'pages-editor-'.uniqid().'@example.com',
        'password' => 'password',
    ]);
    $editor->givePermissionTo('manage_pages');

    actingAs($editor, adminium_guard())
        ->get(route('admin.pages.index'))
        ->assertSuccessful()
        ->assertDontSee('Reports');
});

test('navigation items use their active route patterns', function () {
    actingAs(reportsModuleAdmin(), adminium_guard())
        ->get(route('admin.reports-test'))
        ->assertSuccessful()
        ->assertSee('bg-gray-50 text-indigo-600 dark:bg-white/5 dark:text-white', false);
});

test('custom permissions are available in the user forms', function () {
    actingAs(reportsModuleAdmin(), adminium_guard())
        ->get(route('admin.users.create'))
        ->assertSuccessful()
        ->assertSee('View reports')
        ->assertSee('view_reports', false);
});
