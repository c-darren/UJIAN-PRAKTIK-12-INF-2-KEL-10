@section('tabs')
<div class="border-gray-200 dark:border-gray-700 dark:bg-gray-800">
    <ul class="flex flex-wrap -mb-px text-sm font-medium text-center text-gray-500 dark:text-gray-400">
        <li class="me-2">
            <a href="{{ route('student.classroom.resources.index', [$masterClass_id, $classList->id]) }}" class="inline-flex items-center justify-center p-4 group
                {{ request()->segment(4) === 'resources' && request()->segment(5) == '' ? 
                'text-blue-600 border-b-2 border-blue-600 rounded-t-lg active dark:text-blue-500 dark:border-blue-500' :
                'border-b-2 border-transparent rounded-t-lg hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300'}}"
                {{ request()->segment(4) === 'resources' && request()->segment(5) == '' ? 'aria-current="page"' : '' }}>
                
                Forum
            </a>
        </li>
        <li class="me-2">
            <a href="{{ route('student.classroom.person.all', [$masterClass_id, $classList->id]) }}" class="inline-flex items-center justify-center p-4 group
                {{ request()->segment(5) === 'all' ? 
                'text-blue-600 border-b-2 border-blue-600 rounded-t-lg active dark:text-blue-500 dark:border-blue-500' :
                'border-b-2 border-transparent rounded-t-lg hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300'}}"
                {{ request()->segment(5) === 'all' ? 'aria-current="page"' : '' }}>
                
                Orang
            </a>
        </li>
        <li class="me-2">
            <a href="{{ route('student.classroom.presence.index', [$masterClass_id, $classList->id]) }}" class="inline-flex items-center justify-center p-4 group
                {{ request()->segment(5) === 'kehadiran' ? 
                'text-blue-600 border-b-2 border-blue-600 rounded-t-lg active dark:text-blue-500 dark:border-blue-500' :
                'border-b-2 border-transparent rounded-t-lg hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300'}}"
                {{ request()->segment(5) === 'kehadiran' ? 'aria-current="page"' : '' }}>
                
                Kehadiran
            </a>
        </li>
    </ul>
</div>
@endsection