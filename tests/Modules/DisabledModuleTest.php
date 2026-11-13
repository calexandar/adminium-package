<?php

declare(strict_types=1);

namespace Calexandar\Adminium\Tests\Modules;

use Calexandar\Adminium\Navigation\NavigationRegistry;
use Calexandar\Adminium\Permissions\PermissionRegistry;
use Calexandar\Adminium\Tests\DisabledModuleTestCase;
use Calexandar\Adminium\UserManagment\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Route;
use Spatie\Permission\Models\Role;

final class DisabledModuleTest extends DisabledModuleTestCase
{
    use RefreshDatabase;

    public function test_a_disabled_module_does_not_register_routes_or_navigation(): void
    {
        $navigation = $this->app->make(NavigationRegistry::class);

        $this->assertFalse(Route::has('admin.news.index'));
        $this->assertFalse($navigation->has('news'));
    }

    public function test_a_disabled_module_still_registers_its_permissions(): void
    {
        $permissions = $this->app->make(PermissionRegistry::class);

        $this->assertTrue($permissions->has('manage_news'));
        $this->assertSame('news', $permissions->get('manage_news')?->module);
    }

    public function test_enabled_modules_still_register_routes_and_navigation(): void
    {
        $navigation = $this->app->make(NavigationRegistry::class);

        $this->assertTrue(Route::has('admin.pages.index'));
        $this->assertTrue($navigation->has('pages'));
    }

    public function test_the_admin_layout_renders_without_disabled_settings_links(): void
    {
        Role::findOrCreate('super_admin', adminium_permission_guard());

        $user = User::query()->create([
            'name' => 'Disabled Module Admin',
            'email' => 'disabled-modules@example.com',
            'password' => 'password',
        ]);
        $user->assignRole('super_admin');

        $this->actingAs($user, adminium_guard())
            ->get(route('admin.pages.index'))
            ->assertSuccessful()
            ->assertDontSee('Homepage');
    }
}
