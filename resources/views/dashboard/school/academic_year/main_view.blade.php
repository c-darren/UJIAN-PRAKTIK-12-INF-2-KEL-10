@extends('dashboard.components.layout')

@section('title', 'Academic Years')

@section('content')
    @include('dashboard.school.academic_year.menu')
    @yield('tabs')

    @include('dashboard.school.academic_year.' . $page_content)
    @yield('page_content')
    @yield('scripts')
@endsection