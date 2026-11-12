@extends('dashboard::admin_layout')

@section('content')
    @include('articles::_form', [
        'article' => $article,
        'groups' => $groups,
        'users' => $users,
        'action' => route('admin.articles.update', $article),
        'method' => 'PUT',
    ])
@endsection
