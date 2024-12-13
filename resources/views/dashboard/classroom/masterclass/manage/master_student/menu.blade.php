@section('tabs')
<div class="border-b border-gray-200 dark:border-gray-700 dark:bg-gray-800">
    <ul class="flex flex-wrap -mb-px text-sm font-medium text-center text-gray-500 dark:text-gray-400">
        <li class="me-2">
                <a href="{{ route('classroom.masterClass.manage.index', [$masterClass_id]) }}" class="inline-flex items-center justify-center p-4 group
                {{ (request()->segment(3) === 'manage' && request()->segment(4) === null) || request()->segment(5) === 'class_lists' ? 
                'text-blue-600 border-b-2 border-blue-600 rounded-t-lg active dark:text-blue-500 dark:border-blue-500' :
                'border-b-2 border-transparent rounded-t-lg hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300'}}"
                {{ (request()->segment(3) === 'manage' && request()->segment(4) === null) || request()->segment(5) === 'class_lists' ? 'aria-current="page"' : '' }}>
                
                <svg class="w-6 h-6 mr-1.5 {{ (request()->segment(3) === 'manage' && request()->segment(4) === null) || request()->segment(5) === 'class_lists' ? 'text-blue-800 dark:text-blue-500' : 'dark:text-white text-gray-900'}}" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 4h3a1 1 0 0 1 1 1v15a1 1 0 0 1-1 1H6a1 1 0 0 1-1-1V5a1 1 0 0 1 1-1h3m0 3h6m-3 5h3m-6 0h.01M12 16h3m-6 0h.01M10 3v4h4V3h-4Z"/>
                </svg>  
                Available Class Lists
            </a>
        </li>
        <li class="me-2">
            <a href="{{ route('master_class_students.view_students', [$masterClass_id]) }}" class="inline-flex items-center justify-center p-4 group
                {{ (request()->segment(5) === 'students' && request()->segment(6) === null) ? 
                'text-blue-600 border-b-2 border-blue-600 rounded-t-lg active dark:text-blue-500 dark:border-blue-500' :
                'border-b-2 border-transparent rounded-t-lg hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300'}}"
                {{ (request()->segment(5) === 'students' && request()->segment(6) === null) ? 'aria-current="page"' : '' }}>
                
                <svg class="w-6 h-6 mr-1.5 {{ (request()->segment(5) === 'students' && request()->segment(6) === null) ? 'text-blue-800 dark:text-blue-500' : 'dark:text-white text-gray-900'}}" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                    <path stroke="currentColor" stroke-linecap="round" stroke-width="2" d="M16 19h4a1 1 0 0 0 1-1v-1a3 3 0 0 0-3-3h-2m-2.236-4a3 3 0 1 0 0-4M3 18v-1a3 3 0 0 1 3-3h4a3 3 0 0 1 3 3v1a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1Zm8-10a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/>
                </svg>
                Master Class Students
            </a>
        </li>
        <li class="me-2">
            <a href="{{ route('master_class_students.create_students', [$masterClass_id]) }}" class="inline-flex items-center justify-center p-4 group
                {{ request()->segment(6) === 'create' ? 
                'text-blue-600 border-b-2 border-blue-600 rounded-t-lg active dark:text-blue-500 dark:border-blue-500' :
                'border-b-2 border-transparent rounded-t-lg hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300'}}"
                {{ request()->segment(6) === 'create' ? 'aria-current="page"' : '' }}>

                <svg class="w-6 h-6 mr-1.5 {{ request()->segment(6) === 'create' ? 'text-blue-800 dark:text-blue-500' : 'dark:text-white text-gray-900'}}" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                    <path fill-rule="evenodd" d="M2 12C2 6.477 6.477 2 12 2s10 4.477 10 10-4.477 10-10 10S2 17.523 2 12Zm11-4.243a1 1 0 1 0-2 0V11H7.757a1 1 0 1 0 0 2H11v3.243a1 1 0 1 0 2 0V13h3.243a1 1 0 1 0 0-2H13V7.757Z" clip-rule="evenodd"/>
                </svg>
                New Student
            </a>
        </li>
    </ul>
</div>

@endsection