@extends('dashboard.components.layout')

@section('content')
    @include('dashboard.page_access.route.menu')
    @yield('tabs')

    @include('dashboard.page_access.route.' . $page_content)
    @yield('page_access_content')
    
@endsection