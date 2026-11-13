<?php

declare(strict_types=1);

namespace Calexandar\Adminium\Articles;

use Calexandar\Adminium\Groups\Group;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

final readonly class ArticlesController
{
    public function index(): View
    {
        $articles = Article::query()
            ->orderBy('order', 'asc')
            ->paginate(10);

        return view('articles::index', compact('articles'));
    }

    public function create(): View
    {
        $groups = Group::all();
        $users = adminium_user_model()::query()->get();

        return view('articles::create', compact('groups', 'users'));
    }

    public function store(CreateArticleRequest $request): RedirectResponse
    {

        $article = Article::create([
            'group_id' => $request->integer('group_id'),
            'author_id' => $request->integer('author_id'),
            'title' => $request->array('title'),
            'slug' => $request->string('slug'),
            'content' => $request->array('content'),
            'short_description' => $request->array('short_description'),
            'meta_title' => $request->array('meta_title'),
            'meta_description' => $request->array('meta_description'),
            'published' => $request->boolean('published'),
        ]);

        $article->addMediaFromRequest('cover_image')->toMediaCollection('articles');

        return redirect()->route('admin.articles.index')->with('success', 'Article created successfully');
    }

    public function edit(string $article): View
    {
        $article = Article::find($article);
        $groups = Group::all();
        $users = adminium_user_model()::query()->get();

        return view('articles::edit', compact('article', 'groups', 'users'));
    }

    public function update(UpdateArticleRequest $request, string $article): RedirectResponse
    {
        $article = Article::find($article);

        $article->fill($request->validated());

        if ($request->hasFile('cover_image')) {
            $article->clearMediaCollection('articles');
            $article->addMediaFromRequest('cover_image')->toMediaCollection('articles');
        }

        $article->save();

        return redirect()->route('admin.articles.index')->with('success', 'Article updated successfully');
    }

    public function destroy(string $article): RedirectResponse
    {
        $article = Article::find($article);

        $article->delete();

        return redirect()->route('admin.articles.index')->with('success', 'Article deleted successfully');
    }

    public function reorder(Request $request): RedirectResponse
    {
        $order = $request->input('order');

        foreach ($order as $item) {
            // Find the item in your database by its ID and update its order
            Article::where('id', $item['id'])->update(['order' => $item['order']]);
        }

        return redirect()->route('admin.articles.index');
    }
}
