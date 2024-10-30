@extends('dashboard.components.layout')

@section('content')
    @include('dashboard.authentication.roles.menu')
    @yield('tabs')

    @include('dashboard.authentication.roles.' . $page_content)
    @yield('roles_content')
    @yield('scripts')
@endsection