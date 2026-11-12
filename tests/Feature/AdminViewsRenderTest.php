<?php

declare(strict_types=1);

use Calexandar\Adminium\UserManagment\User;
use Spatie\Permission\Models\Role;

use function Pest\Laravel\actingAs;

function adminUser(): User
{
    Role::query()->firstOrCreate(['name' => 'super_admin', 'guard_name' => 'web']);

    $user = User::query()->create([
        'name' => 'Test User',
        'email' => 'render-'.uniqid().'@example.com',
        'password' => bcrypt('password'),
    ]);
    $user->assignRole('super_admin');

    return $user;
}

test('all module create views render', function (string $module) {
    actingAs(adminUser())
        ->get(route("admin.{$module}.create"))
        ->assertSuccessful();
})->with(['categories', 'groups', 'media', 'news', 'pages', 'products']);

test('all module index views render', function (string $module) {
    actingAs(adminUser())
        ->get(route("admin.{$module}.index"))
        ->assertSuccessful();
})->with(['categories', 'groups', 'media', 'news', 'pages', 'products']);
test('all module edit views render', function (string $module) {
    $models = [
        'categories' => Calexandar\Adminium\Categories\Category::class,
        'groups' => Calexandar\Adminium\Groups\Group::class,
        'media' => Calexandar\Adminium\Media\Media::class,
        'news' => Calexandar\Adminium\News\News::class,
        'pages' => Calexandar\Adminium\Pages\Page::class,
        'products' => Calexandar\Adminium\Products\Product::class,
    ];

    $class = $models[$module];

    $category = Calexandar\Adminium\Categories\Category::query()->create([
        'title' => ['en' => 'Test Category'],
        'description' => ['en' => 'Test description'],
        'caption' => ['en' => 'Test caption'],
        'meta_title' => ['en' => 'Test meta title'],
        'meta_description' => ['en' => 'Test meta description'],
        'slug' => 'test-category',
        'published' => true,
        'order' => 1,
    ]);

    $model = $class::query()->create([
        'title' => ['en' => 'Test '.$module],
        'description' => ['en' => 'Test description'],
        'caption' => ['en' => 'Test caption'],
        'content' => ['en' => 'Test content'],
        'subtitle' => ['en' => 'Test subtitle'],
        'disclaimer' => ['en' => 'Test disclaimer'],
        'meta_title' => ['en' => 'Test meta title'],
        'meta_description' => ['en' => 'Test meta description'],
        'slug' => 'test-'.$module,
        'published' => true,
        'order' => 1,
        'in_menu' => false,
        'privacy_policy' => false,
        'new_product' => false,
        'category_id' => $category->id,
    ]);

    actingAs(adminUser())
        ->get(route("admin.{$module}.edit", $model))
        ->assertSuccessful();
})->with(['categories', 'groups', 'media', 'news', 'pages', 'products']);
