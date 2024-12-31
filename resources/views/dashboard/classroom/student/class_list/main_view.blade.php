@extends('dashboard.components.layout')

@section('content')
    @php
        $segment = request()->segment(2);
    @endphp
    @include('dashboard.classroom.student.class_list.menu')
    @yield('tabs')

    @include('dashboard.classroom.student.class_list.' . $page_content)
    @yield('page_content')
    @yield('scripts')
@endsection