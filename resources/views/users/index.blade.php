@extends('layouts.default')
@section('title', '所有用户')

@section('content')
<div class="col-md-offset-2 col-md-8">
    <h1>用户列表</h1>
    <ul class="users">
        @foreach ($users as $user)
        @include('users._user')
        @endforeach
    </ul>
    {!! $users->links() !!}
</div>    
@endsection
