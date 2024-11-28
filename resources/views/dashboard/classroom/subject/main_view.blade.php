@extends('dashboard.components.layout')

@section('title', 'Subjects Management')

@section('content')
    @include('dashboard.classroom.subject.menu')
    @yield('tabs')

    @include('dashboard.classroom.subject.' . $page_content)
    @yield('page_content')
    @yield('scripts')
@endsection