@section('tabs')
<div class="border-b border-gray-200 dark:border-gray-700 dark:bg-gray-800">
    <ul class="flex flex-wrap -mb-px text-sm font-medium text-center text-gray-500 dark:text-gray-400">
        <li class="me-2">
            <a href="{{ route('admin.page_access.route.view') }}" class="inline-flex items-center justify-center p-4 group
                {{ request()->segment(4) === 'view' ? 
                'text-blue-600 border-b-2 border-blue-600 rounded-t-lg active dark:text-blue-500 dark:border-blue-500' :
                'border-b-2 border-transparent rounded-t-lg hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300'}}"
                {{ request()->segment(4) === 'view' ? 'aria-current="page"' : '' }}>
                
                <svg class="w-4 h-4 me-2
                {{ request()->segment(4) === 'view' ? 'text-blue-600 dark:text-blue-500' : 'text-gray-400 group-hover:text-gray-500 dark:text-gray-500 dark:group-hover:text-gray-300'}}" 
                aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6h8m-8 6h8m-8 6h8M4 16a2 2 0 1 1 3.321 1.5L4 20h5M4 5l2-1v6m-2 0h4"/>
                </svg>
                Route List
            </a>
        </li>
        <li class="me-2">
            <a href="{{ route('admin.page_access.route.create') }}" class="inline-flex items-center justify-center p-4 group
            {{ request()->segment(4) === 'create' ? 
            'text-blue-600 border-b-2 border-blue-600 rounded-t-lg active dark:text-blue-500 dark:border-blue-500' :
            'border-b-2 border-transparent rounded-t-lg hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300'}}"
            {{ request()->segment(4) === 'create' ? 'aria-current="page"' : '' }}>
                <svg class="w-4 h-4 me-2
                {{ request()->segment(4) === 'create' ? ' text-blue-600 dark:text-blue-500' : ' text-gray-400 group-hover:text-gray-500 dark:text-gray-500 dark:group-hover:text-gray-300'}}" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">                
                    <path d="M6.143 0H1.857A1.857 1.857 0 0 0 0 1.857v4.286C0 7.169.831 8 1.857 8h4.286A1.857 1.857 0 0 0 8 6.143V1.857A1.857 1.857 0 0 0 6.143 0Zm10 0h-4.286A1.857 1.857 0 0 0 10 1.857v4.286C10 7.169 10.831 8 11.857 8h4.286A1.857 1.857 0 0 0 18 6.143V1.857A1.857 1.857 0 0 0 16.143 0Zm-10 10H1.857A1.857 1.857 0 0 0 0 11.857v4.286C0 17.169.831 18 1.857 18h4.286A1.857 1.857 0 0 0 8 16.143v-4.286A1.857 1.857 0 0 0 6.143 10Zm10 0h-4.286A1.857 1.857 0 0 0 10 11.857v4.286c0 1.026.831 1.857 1.857 1.857h4.286A1.857 1.857 0 0 0 18 16.143v-4.286A1.857 1.857 0 0 0 16.143 10Z"/>
                </svg>New Route
            </a>
        </li>
    </ul>
</div>
@endsection