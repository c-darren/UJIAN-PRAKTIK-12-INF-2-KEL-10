@extends('dashboard.components.layout')

@section('title', $page_title)

@section('content')
    @php
        $segment = request()->segment(2);
    @endphp
    @include('dashboard.classroom.class_list.resource.menu')
    @yield('tabs')

    @include('dashboard.classroom.class_list.resource.' . $page_content)
    @yield('page_content')
    @yield('scripts')
@endsection