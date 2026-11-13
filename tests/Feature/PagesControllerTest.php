<?php

declare(strict_types=1);

namespace Calexandar\Adminium\Tests\Feature;

use Calexandar\Adminium\Pages\Page;
use Calexandar\Adminium\UserManagment\User;
use Illuminate\Http\UploadedFile;
use Spatie\Permission\Models\Role;

use function Pest\Laravel\actingAs;

test('can view pages index', function () {
    Role::query()->create(['name' => 'super_admin', 'guard_name' => 'web']);

    $user = User::query()->create([
        'name' => 'Test User',
        'email' => 'test@example.com',
        'password' => bcrypt('password'),
    ]);
    $user->assignRole('super_admin');

    Page::query()->create([
        'title' => ['en' => 'Test Page'],
        'content' => ['en' => 'Test content'],
        'subtitle' => ['en' => 'Test subtitle'],
        'meta_title' => ['en' => 'Test meta title'],
        'meta_description' => ['en' => 'Test meta description'],
        'slug' => 'test-page-1',
        'published' => true,
        'order' => 1,
    ]);

    actingAs($user, adminium_guard())
        ->get(route('admin.pages.index'))
        ->assertSuccessful()
        ->assertViewIs('pages::index')
        ->assertViewHas('pages');
});

test('can create page', function () {
    Role::query()->create(['name' => 'super_admin', 'guard_name' => 'web']);

    $user = User::query()->create([
        'name' => 'Test User',
        'email' => 'test@example.com',
        'password' => bcrypt('password'),
    ]);
    $user->assignRole('super_admin');

    $data = [
        'title' => ['en' => 'Test Page'],
        'slug' => 'test-page',
        'content' => ['en' => 'Test content'],
        'subtitle' => ['en' => 'Test subtitle'],
        'meta_title' => ['en' => 'Test Meta Title'],
        'meta_description' => ['en' => 'Test meta description'],
        'cover_image' => UploadedFile::fake()->image('cover.jpg'),
    ];

    actingAs($user, adminium_guard())
        ->post(route('admin.pages.store'), $data)
        ->assertRedirect(route('admin.pages.index'))
        ->assertSessionHas('success', 'Page created successfully');

    expect(Page::where('slug', 'test-page')->exists())->toBeTrue();
});

test('can update page', function () {
    Role::query()->create(['name' => 'super_admin', 'guard_name' => 'web']);

    $user = User::query()->create([
        'name' => 'Test User',
        'email' => 'test@example.com',
        'password' => bcrypt('password'),
    ]);
    $user->assignRole('super_admin');

    $page = Page::query()->create([
        'title' => ['en' => 'Old Page'],
        'content' => ['en' => 'Old content'],
        'subtitle' => ['en' => 'Old subtitle'],
        'meta_title' => ['en' => 'Old meta title'],
        'meta_description' => ['en' => 'Old meta description'],
        'slug' => 'old-page',
        'published' => false,
        'in_menu' => false,
        'privacy_policy' => false,
    ]);

    $data = [
        'title' => ['en' => 'Updated Page'],
        'slug' => 'updated-page',
        'content' => ['en' => 'Updated content'],
        'subtitle' => ['en' => 'Updated subtitle'],
        'meta_title' => ['en' => 'Updated Meta Title'],
        'meta_description' => ['en' => 'Updated meta description'],
        'published' => true,
        'in_menu' => false,
        'privacy_policy' => false,
    ];

    actingAs($user, adminium_guard())
        ->put(route('admin.pages.update', $page), $data)
        ->assertRedirect(route('admin.pages.index'))
        ->assertSessionHas('success', 'Page updated successfully');

    $page->refresh();
    expect($page->getTranslation('title', 'en'))->toBe('Updated Page');
});

test('can delete page', function () {
    Role::query()->create(['name' => 'super_admin', 'guard_name' => 'web']);

    $user = User::query()->create([
        'name' => 'Test User',
        'email' => 'test@example.com',
        'password' => bcrypt('password'),
    ]);
    $user->assignRole('super_admin');

    $page = Page::query()->create([
        'title' => ['en' => 'Test Page'],
        'content' => ['en' => 'Test content'],
        'subtitle' => ['en' => 'Test subtitle'],
        'meta_title' => ['en' => 'Test meta title'],
        'meta_description' => ['en' => 'Test meta description'],
        'slug' => 'test-page',
        'published' => true,
    ]);

    actingAs($user, adminium_guard())
        ->delete(route('admin.pages.destroy', $page))
        ->assertRedirect(route('admin.pages.index'))
        ->assertSessionHas('success', 'Page deleted successfully');

    expect(Page::find($page->id))->toBeNull();
});
