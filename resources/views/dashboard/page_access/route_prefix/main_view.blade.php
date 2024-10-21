@extends('dashboard.components.layout')

@section('content')
    @include('dashboard.page_access.route_prefix.menu')
    @yield('tabs')

    @include('dashboard.page_access.route_prefix.' . $page_content)
    @yield('page_access_content')
    @yield('scripts')
@endsection