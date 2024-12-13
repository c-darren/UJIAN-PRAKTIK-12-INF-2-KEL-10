@section('tabs')
<div class="border-b border-gray-200 dark:border-gray-700 dark:bg-gray-800">
    <ul class="flex flex-wrap -mb-px text-sm font-medium text-center text-gray-500 dark:text-gray-400">
        <li class="me-2">
            <a href="{{ route('class_lists.teacher.index', [$masterClass_id, $classList_id]) }}" class="inline-flex items-center justify-center p-4 group
                {{ (request()->segment(3) === 'manage' && request()->segment(4) === null) || request()->segment(7) === 'class_lists' ? 
                'text-blue-600 border-b-2 border-blue-600 rounded-t-lg active dark:text-blue-500 dark:border-blue-500' :
                'border-b-2 border-transparent rounded-t-lg hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300'}}"
                {{ (request()->segment(3) === 'manage' && request()->segment(4) === null) || request()->segment(7) === 'class_lists' ? 'aria-current="page"' : '' }}>
                
                <svg class="w-6 h-6 mr-1.5 {{ (request()->segment(3) === 'manage' && request()->segment(4) === null) || request()->segment(7) === 'class_lists' ? 'text-blue-800 dark:text-blue-500' : 'dark:text-white text-gray-900'}}" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 21a9 9 0 1 0 0-18 9 9 0 0 0 0 18Zm0 0a8.949 8.949 0 0 0 4.951-1.488A3.987 3.987 0 0 0 13 16h-2a3.987 3.987 0 0 0-3.951 3.512A8.948 8.948 0 0 0 12 21Zm3-11a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/>
                </svg>
                Teachers
            </a>
        </li>
        <li class="me-2">
            <a href="{{ route('class_lists.student.index', [$masterClass_id, $classList_id]) }}" class="inline-flex items-center justify-center p-4 group
                {{ request()->segment(7) === 'student' ? 
                'text-blue-600 border-b-2 border-blue-600 rounded-t-lg active dark:text-blue-500 dark:border-blue-500' :
                'border-b-2 border-transparent rounded-t-lg hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300'}}"
                {{ request()->segment(7) === 'student' ? 'aria-current="page"' : '' }}>
                
                <svg class="w-6 h-6 mr-1.5 {{ (request()->segment(7) === 'student' && request()->segment(8) === null) ? 'text-blue-800 dark:text-blue-500' : 'dark:text-white text-gray-900'}}" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                    <path stroke="currentColor" stroke-linecap="round" stroke-width="2" d="M16 19h4a1 1 0 0 0 1-1v-1a3 3 0 0 0-3-3h-2m-2.236-4a3 3 0 1 0 0-4M3 18v-1a3 3 0 0 1 3-3h4a3 3 0 0 1 3 3v1a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1Zm8-10a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/>
                </svg>
                
                Class Students
            </a>
        </li>
        @if (isset($showVerificationAlert) && $showVerificationAlert)

        @else
        @endif
    </ul>
</div>
@endsection