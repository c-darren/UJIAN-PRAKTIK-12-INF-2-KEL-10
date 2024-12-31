@section('tabs')
<div class="border-b border-gray-200 dark:border-gray-700 dark:bg-gray-800">
    <ul class="flex flex-wrap -mb-px text-sm font-medium text-center text-gray-500 dark:text-gray-400">
        <li class="me-2">
            <a href="{{ route('master-class.enrolled-class') }}" class="inline-flex items-center justify-center p-4 group
                {{ (request()->segment(1) === 'master-classes' && request()->segment(2) === null) || request()->segment(1) === 'dashboard' ? 
                'text-blue-600 border-b-2 border-blue-600 rounded-t-lg active dark:text-blue-500 dark:border-blue-500' :
                'border-b-2 border-transparent rounded-t-lg hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300'}}"
                {{ (request()->segment(1) === 'master-classes' && request()->segment(2) === null) || request()->segment(1) === 'dashboard' ? 'aria-current="page"' : '' }}>
                
                <svg class="w-6 h-6 mr-1.5 {{ (request()->segment(1) === 'master-classes' && request()->segment(2) === null) || request()->segment(1) === 'dashboard' ? 'text-blue-800 dark:text-blue-500' : 'dark:text-white text-gray-900'}}" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 4h3a1 1 0 0 1 1 1v15a1 1 0 0 1-1 1H6a1 1 0 0 1-1-1V5a1 1 0 0 1 1-1h3m0 3h6m-3 5h3m-6 0h.01M12 16h3m-6 0h.01M10 3v4h4V3h-4Z"/>
                </svg>  
                Enrolled
            </a>
        </li>
        <li class="me-2">
            <a href="{{ route('master-class.archived-class') }}" class="inline-flex items-center justify-center p-4 group
                {{ request()->segment(2) === 'archived-class' ? 
                'text-blue-600 border-b-2 border-blue-600 rounded-t-lg active dark:text-blue-500 dark:border-blue-500' :
                'border-b-2 border-transparent rounded-t-lg hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300'}}"
                {{ request()->segment(2) === 'archived-class' ? 'aria-current="page"' : '' }}>
                
                <svg class="w-6 h-6 mr-1.5 {{ request()->segment(2) === 'archived-class' ? 'text-blue-800 dark:text-blue-500' : 'dark:text-white text-gray-900'}}" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 11v5m0 0 2-2m-2 2-2-2M3 6v1a1 1 0 0 0 1 1h16a1 1 0 0 0 1-1V6a1 1 0 0 0-1-1H4a1 1 0 0 0-1 1Zm2 2v10a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V8H5Z"/>
                </svg>
                Archived
            </a>
        </li>
        <li class="me-2">
            <a href="{{ route('master-class.exited-class') }}" class="inline-flex items-center justify-center p-4 group
                {{ request()->segment(2) === 'exited-class' ? 
                'text-blue-600 border-b-2 border-blue-600 rounded-t-lg active dark:text-blue-500 dark:border-blue-500' :
                'border-b-2 border-transparent rounded-t-lg hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300'}}"
                {{ request()->segment(2) === 'exited-class' ? 'aria-current="page"' : '' }}>
                
                <svg class="w-6 h-6 mr-1.5 {{ request()->segment(2) === 'exited-class' ? 'text-blue-800 dark:text-blue-500' : 'dark:text-white text-gray-900'}}" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12H4m12 0-4 4m4-4-4-4m3-4h2a3 3 0 0 1 3 3v10a3 3 0 0 1-3 3h-2"/>
                </svg>
                Exited
            </a>
        </li>
        @if (isset($showVerificationAlert) && $showVerificationAlert)

        @else
            @if(request()->segment(2) === null)
            <li class="me-2">
                <button onclick="clickCreateModalButton()" class="inline-flex items-center justify-center p-4 group'border-b-2 border-transparent rounded-t-lg hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300"
                >
                <svg class="w-6 h-6 mr-1.5 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                    <path fill-rule="evenodd" d="M2 12C2 6.477 6.477 2 12 2s10 4.477 10 10-4.477 10-10 10S2 17.523 2 12Zm11-4.243a1 1 0 1 0-2 0V11H7.757a1 1 0 1 0 0 2H11v3.243a1 1 0 1 0 2 0V13h3.243a1 1 0 1 0 0-2H13V7.757Z" clip-rule="evenodd"/>
                </svg>              
                Enroll
                </button>
            </li>
            @endif
        @endif
    </ul>
</div>

@if (isset($showVerificationAlert) && $showVerificationAlert)
<div class="mr-4 ml-4 mt-5">
    <div id="toast-warning" class="flex items-center w-full p-4 text-gray-500 bg-white rounded-lg shadow dark:text-gray-400 dark:bg-gray-800" role="alert">
        <div class="inline-flex items-center justify-center flex-shrink-0 w-8 h-8 text-orange-500 bg-yellow-100 rounded-lg dark:bg-yellow-700 dark:text-orange-200">
            <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM10 15a1 1 0 1 1 0-2 1 1 0 0 1 0 2Zm1-4a1 1 0 0 1-2 0V6a1 1 0 0 1 2 0v5Z"/>
            </svg>
            <span class="sr-only">Email Verification Required</span>
        </div>
        <div class="ms-3 text-sm font-normal">
            Your email has not been verified yet. Please verify your email to unlock all features.
            <form id="resend-form" method="POST" action="{{ route('verification.resend') }}">
                @csrf
                <button onclick="submitResendEmailForm()" id="resend-button" type="button" class="text-white mt-2 ml-1 bg-green-700 hover:bg-green-800 px-3 py-1.5 rounded-full disabled:opacity-50 disabled:cursor-not-allowed">
                    Resend Verification Email
                </button>
            </form>
        </div>
        <button type="button" class="ms-auto -mx-1.5 -my-1.5 bg-white text-gray-400 hover:text-gray-900 rounded-lg focus:ring-2 focus:ring-gray-300 p-1.5 hover:bg-gray-100 inline-flex items-center justify-center h-8 w-8 dark:text-gray-500 dark:hover:text-white dark:bg-gray-800 dark:hover:bg-gray-700" data-dismiss-target="#toast-warning" aria-label="Close">
            <span class="sr-only">Close</span>
            <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
            </svg>
        </button>
    </div>
</div>
@else
@endif

@endsection