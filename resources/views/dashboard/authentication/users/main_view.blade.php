@extends('dashboard.components.layout')

@section('content')
    @include('dashboard.authentication.users.menu')
    @yield('tabs')

    @include('dashboard.authentication.users.' . $page_content)
    @yield('users_content')
@endsection