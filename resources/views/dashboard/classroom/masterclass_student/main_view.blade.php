@extends('dashboard.components.layout')

@section('content')
    @php
        $segment = request()->segment(2);
    @endphp
    @include('dashboard.classroom.masterclass_student.menu')
    @yield('tabs')

    @include('dashboard.classroom.masterclass_student.' . $page_content)
    @yield('page_content')
    @yield('scripts')
@endsection

@section('required_scripts')
@if (isset($showVerificationAlert) && $showVerificationAlert)
<script src="{{ asset('js/authentication/email/verification.js') }}"></script>
@endif
<script src="{{ asset('js/classroom/masterclass/student_join.js') }}"></script>
    @switch($segment)
        @case('archived-class')
            @break
        @case('exited-class')
            <script src="{{ asset('js/classroom/masterclass/student_rejoin.js') }}"></script>
            @break
        @default
            <script src="{{ asset('js/classroom/masterclass/student_exit.js') }}"></script>
    @endswitch
@endsection