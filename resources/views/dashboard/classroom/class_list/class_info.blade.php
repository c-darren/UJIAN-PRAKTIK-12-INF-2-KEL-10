@extends('dashboard.components.layout')
@section('title', $page_title)
@section('content')
<div class="p-4 bg-white dark:bg-gray-800 border border-gray-200 rounded-lg shadow dark:border-gray-700">
    <h2 class="text-lg font-semibold mb-4 text-gray-900 dark:text-white">Class Information</h2>
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
        <div class="bg-white dark:bg-gray-700 p-4 rounded-lg shadow border border-gray-300 dark:border-gray-600">
            <h3 class="text-md font-semibold text-gray-900 dark:text-white mb-2">Master Class Details</h3>
            <p class="text-sm text-gray-700 dark:text-gray-300"><strong>Master Class ID:</strong> {{ $masterClass_id }}</p>
            <p class="text-sm text-gray-700 dark:text-gray-300"><strong>Master Class Name:</strong> {{ $masterClass_name }}</p>
            <p class="text-sm text-gray-700 dark:text-gray-300"><strong>Master Class Status:</strong> {{ $master_class_status }}</p>
            <p class="text-sm text-gray-700 dark:text-gray-300"><strong>Master Class Code:</strong> {{ $master_class_code }}</p>
        </div>
        <div class="bg-white dark:bg-gray-700 p-4 rounded-lg shadow border border-gray-300 dark:border-gray-600">
            <h3 class="text-md font-semibold text-gray-900 dark:text-white mb-2">Class List Details</h3>
            <p class="text-sm text-gray-700 dark:text-gray-300"><strong>Class List ID:</strong> {{ $classList->id }}</p>
            <p class="text-sm text-gray-700 dark:text-gray-300"><strong>Class Name:</strong> {{ $classList->class_name }}</p>
            <p class="text-sm text-gray-700 dark:text-gray-300"><strong>Enrollment Status:</strong> {{ $classList->enrollment_status }}</p>
            <p class="text-sm text-gray-700 dark:text-gray-300"><strong>Mata Pelajaran:</strong> {{ $subject_name }}</p>
        </div>
    </div>
</div>
@endsection