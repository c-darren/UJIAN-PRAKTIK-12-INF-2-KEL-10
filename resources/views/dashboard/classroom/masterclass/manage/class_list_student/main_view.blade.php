@extends('dashboard.components.layout')

@section('content')
    @php
        $segment = request()->segment(2);
    @endphp
    @include('dashboard.classroom.masterclass.manage.class_list_student.menu')
    @yield('tabs')

    @include('dashboard.classroom.masterclass.manage.class_list_student.' . $page_content)
    @yield('page_content')
    @yield('scripts')
@endsection