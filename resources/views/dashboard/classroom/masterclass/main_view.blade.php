@extends('dashboard.components.layout')

@section('title', 'Master Class Management')

@section('content')
    @include('dashboard.classroom.masterclass.menu')
    @yield('tabs')

    @include('dashboard.classroom.masterclass.' . $page_content)
    @yield('page_content')
    @yield('scripts')
@endsection