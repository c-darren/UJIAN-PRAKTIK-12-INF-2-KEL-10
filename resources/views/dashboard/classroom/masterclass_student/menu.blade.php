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
<div class="flex flex-col mt-4 ml-4 my-4 mb-1">
    <div x-data="{
        $store: {
            createModal: { open: false },
            @switch($segment)
                @case('archived-class')
                    @break
                @case('exited-class')
                    rejoinModal: { open: false },
                    @break
                @default
                    exitModal: { open: false }
            @endswitch
        },
        clearData() {
            this.data = {};
        }
    }"
    @keydown.escape.window="$store.createModal.close()"
    class="relative">
        <div class="flex flex-col py-1">
            <div class="overflow-x-auto">
                <div class="inline-block min-w-full align-middle">
                </div>
            </div>
        </div>
        @switch($segment)
            @case('archived-class')
                @break
            @case('exited-class')
        <!-- Rejoin Modal -->
        <div x-show="$store.rejoinModal.open"
            x-data="rejoinModalData()"
            x-cloak
            class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900 bg-opacity-75 overflow-y-auto"
            @keydown.escape.window="$store.rejoinModal.close()"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 transform scale-90"
            x-transition:enter-end="opacity-100 transform scale-100"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 transform scale-100"
            x-transition:leave-end="opacity-0 transform scale-90">

            <div @click.away="$store.rejoinModal.close()"
                class="bg-white dark:bg-gray-800 w-full max-w-md p-6 space-y-6 rounded-lg shadow-lg relative overflow-y-auto">
                
                <div class="sticky top-0 bg-white dark:bg-gray-800 z-30 p-4 border-b border-gray-200 dark:border-gray-700 shadow-md">
                    <div class="flex justify-between items-center">
                        <h3 class="text-xl font-semibold text-gray-900 dark:text-white">Rejoin Master Class</h3>
                        <button @click="$store.rejoinModal.close()" class="text-gray-400 hover:text-gray-900 dark:hover:text-white">
                            <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>
                </div>
                <div class="p-4">
                    <form action="{{ route('master-class.rejoin-class') }}" id="rejoinForm" x-ref="rejoinForm" @keydown.enter.prevent="submitrejoinForm">
                        <input type="number" name="m_class_id" x-model="$store.rejoinModal.data.id" id="m_class_id" readonly hidden>
                        <p class="text-gray-700 dark:text-gray-300">Are you sure you want to rejoin?<br>You can exit later as the class is still active.</p>
                    </form>
                </div>
                <div class="flex items-center justify-end space-x-2 border-t bg-white dark:bg-gray-800 border-gray-200 dark:border-gray-700 pt-4 sticky bottom-0">
                    <button @click="$store.rejoinModal.close()" class="text-yellow-400 hover:text-white border border-yellow-400 hover:bg-yellow-500 focus:ring-4 focus:outline-none focus:ring-yellow-300 font-medium text-sm px-5 py-2.5 text-center dark:border-yellow-300 dark:text-yellow-300 dark:hover:text-white dark:hover:bg-yellow-400 dark:focus:ring-yellow-900 rounded-full">Cancel</button>
                    <button @click="submitrejoinForm" class="text-red-700 hover:text-white border border-red-700 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium text-sm px-5 py-2.5 text-center dark:border-red-500 dark:text-red-500 dark:hover:text-white dark:hover:bg-red-600 dark:focus:ring-red-900 rounded-full">Rejoin</button>
                </div>
            </div>
        </div>
                @break
            @default
        @endswitch
            <!-- Exit Modal -->
        <div x-show="$store.exitModal.open"
            x-data="exitModalData()"
            x-cloak
            class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900 bg-opacity-75 overflow-y-auto"
            @keydown.escape.window="$store.exitModal.close()"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 transform scale-90"
            x-transition:enter-end="opacity-100 transform scale-100"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 transform scale-100"
            x-transition:leave-end="opacity-0 transform scale-90">

            <div @click.away="$store.exitModal.close()"
                class="bg-white dark:bg-gray-800 w-full max-w-md p-6 space-y-6 rounded-lg shadow-lg relative overflow-y-auto">
                
                <div class="sticky top-0 bg-white dark:bg-gray-800 z-30 p-4 border-b border-gray-200 dark:border-gray-700 shadow-md">
                    <div class="flex justify-between items-center">
                        <h3 class="text-xl font-semibold text-gray-900 dark:text-white">Exit Master Class</h3>
                        <button @click="$store.exitModal.close()" class="text-gray-400 hover:text-gray-900 dark:hover:text-white">
                            <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>
                </div>
                <div class="p-4">
                    <form action="{{ route('master-class.exit-class') }}" id="exitForm" x-ref="exitForm" @keydown.enter.prevent="submitExitForm">
                        <input type="number" name="m_class_id" x-model="$store.exitModal.data.id" id="m_class_id" readonly hidden>
                        <p class="text-gray-700 dark:text-gray-300">Are you sure you want to exit?<br>You can rejoin later as the class is still active.</p>
                    </form>
                </div>
                <div class="flex items-center justify-end space-x-2 border-t bg-white dark:bg-gray-800 border-gray-200 dark:border-gray-700 pt-4 sticky bottom-0">
                    <button @click="$store.exitModal.close()" class="text-yellow-400 hover:text-white border border-yellow-400 hover:bg-yellow-500 focus:ring-4 focus:outline-none focus:ring-yellow-300 font-medium text-sm px-5 py-2.5 text-center dark:border-yellow-300 dark:text-yellow-300 dark:hover:text-white dark:hover:bg-yellow-400 dark:focus:ring-yellow-900 rounded-full">Cancel</button>
                    <button @click="submitExitForm" class="text-red-700 hover:text-white border border-red-700 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium text-sm px-5 py-2.5 text-center dark:border-red-500 dark:text-red-500 dark:hover:text-white dark:hover:bg-red-600 dark:focus:ring-red-900 rounded-full">Exit</button>
                </div>
            </div>
        </div>
        
        <!-- Create Modal Button -->
        <button @click="$store.createModal.show()" id="showCreateModal" class="hidden"></button>
        <!-- Create Modal -->
        <div x-show="$store.createModal.open"
            x-data="createModalData()"
            x-cloak
            class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900 bg-opacity-75 overflow-y-auto"
            @keydown.escape.window="$store.createModal.close()"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 transform scale-90"
            x-transition:enter-end="opacity-100 transform scale-100"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 transform scale-100"
            x-transition:leave-end="opacity-0 transform scale-90">

            <div @click.away="$store.createModal.close()"
                class="bg-white dark:bg-gray-800 w-full max-w-lg p-6 space-y-6 rounded-lg shadow-lg relative overflow-y-auto">
                
                <div class="sticky top-0 bg-white dark:bg-gray-800 z-30 p-4 border-b border-gray-200 dark:border-gray-700 shadow-md">
                    <div class="flex justify-between items-center">
                        <h3 class="text-xl font-semibold text-gray-900 dark:text-white">Enroll Master Class</h3>
                        <button @click="$store.createModal.close()" class="text-gray-400 hover:text-gray-900 dark:hover:text-white">
                            <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>
                </div>
                <form class="grid grid-cols-1 md:grid-cols-2 gap-5 p-4 max-h-[70vh] overflow-y-auto" id="createForm" x-ref="createForm" @keydown.enter.prevent="submitCreateForm" action="{{ route('master-class.join-class') }}">
                    @csrf
                    <div class="sm:col-span-2">
                        <input type="text" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" id="join_class_code" name="join_class_code" placeholder="type the code to join class">
                    </div>
                </form> 
                <div class="flex items-center justify-end space-x-2 border-t bg-white dark:bg-gray-800 border-gray-200 dark:border-gray-700 pt-4 sticky bottom-0">
                    <button @click="$store.createModal.close()" class="bg-yellow-400 hover:bg-yellow-500 text-white px-4 py-2 rounded">Cancel</button>
                    <button @click="resetCreateForm()" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded">Reset</button>
                    <button @click="submitCreateForm" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded">Enroll</button>
                </div>
            </div>
        </div>
    </div>
</div>
@endif

@endsection