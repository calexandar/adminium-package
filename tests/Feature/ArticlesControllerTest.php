<?php

declare(strict_types=1);

namespace Calexandar\Adminium\Tests\Feature;

use Calexandar\Adminium\Articles\Article;
use Calexandar\Adminium\Groups\Group;
use Calexandar\Adminium\UserManagment\User;
use Illuminate\Http\UploadedFile;
use Spatie\Permission\Models\Role;

use function Pest\Laravel\actingAs;

function createArticleAdmin(): User
{
    Role::query()->create(['name' => 'super_admin', 'guard_name' => 'web']);

    $user = User::query()->create([
        'name' => 'Test User',
        'email' => 'articles@example.com',
        'password' => bcrypt('password'),
    ]);
    $user->assignRole('super_admin');

    return $user;
}

function createArticleGroup(): Group
{
    return Group::query()->create([
        'title' => ['en' => 'Test Group'],
        'slug' => 'test-group',
        'description' => ['en' => 'Test description'],
        'caption' => ['en' => 'Test caption'],
        'published' => true,
    ]);
}

function createArticle(Group $group, User $user): Article
{
    return Article::query()->create([
        'group_id' => $group->id,
        'author_id' => $user->id,
        'title' => ['en' => 'Test Article'],
        'slug' => 'test-article',
        'content' => ['en' => 'Test content'],
        'short_description' => ['en' => 'Test short description'],
        'meta_title' => ['en' => 'Test meta title'],
        'meta_description' => ['en' => 'Test meta description'],
        'published' => true,
    ]);
}

test('can view articles index', function () {
    $user = createArticleAdmin();
    $group = createArticleGroup();
    createArticle($group, $user);

    actingAs($user)
        ->get(route('admin.articles.index'))
        ->assertSuccessful()
        ->assertViewIs('articles::index');
});

test('can view create article form', function () {
    $user = createArticleAdmin();

    actingAs($user)
        ->get(route('admin.articles.create'))
        ->assertSuccessful()
        ->assertViewIs('articles::create');
});

test('can view edit article form', function () {
    $user = createArticleAdmin();
    $group = createArticleGroup();
    $article = createArticle($group, $user);

    actingAs($user)
        ->get(route('admin.articles.edit', $article))
        ->assertSuccessful()
        ->assertViewIs('articles::edit');
});

test('stores the published state when creating an article', function () {
    $user = createArticleAdmin();
    $group = createArticleGroup();

    $data = [
        'title' => ['en' => 'New Article'],
        'slug' => 'new-article',
        'content' => ['en' => 'New content'],
        'short_description' => ['en' => 'New short description'],
        'meta_title' => ['en' => 'New meta title'],
        'meta_description' => ['en' => 'New meta description'],
        'published' => '1',
        'group_id' => $group->id,
        'author_id' => $user->id,
        'cover_image' => UploadedFile::fake()->image('cover.jpg'),
    ];

    actingAs($user)
        ->post(route('admin.articles.store'), $data)
        ->assertRedirect(route('admin.articles.index'))
        ->assertSessionHas('success', 'Article created successfully');

    expect(Article::where('slug', 'new-article')->first()?->published)->toBeTruthy();
});
