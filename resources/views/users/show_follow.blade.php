@extends('layouts.default')
@section('title', $title)

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="col-md-offset-2 col-md-8">
            <section class="user_info">
                @include('shared._user_info', ['user' => $user])
            </section>
            <section class="stats">
                @include('shared._stats', ['user' => $user])
            </section>
        </div>
    </div>
    <div class="col-md-offset-2 col-md-8">
        @if (Auth::check())
        @include('users._follow_form')
        @endif
        <h1>{{ $title }}</h1>
        <ul class="users">
            @foreach ($users as $user)
            <li>
                <img src="{{ $user->gravatar() }}" alt="{{ $user->name }}" class="gravatar"/>
                <a href="{{ route('users.show', $user->id )}}" class="username">{{ $user->name }}</a>
            </li>
            @endforeach
        </ul>

        {!! $users->render() !!}
    </div>
</div>
@endsection