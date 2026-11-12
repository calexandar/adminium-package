@extends('dashboard::admin_layout')

@section('content')
    @include('articles::_form', [
        'article' => null,
        'groups' => $groups,
        'users' => $users,
        'action' => route('admin.articles.store'),
        'method' => 'POST',
    ])
@endsection
