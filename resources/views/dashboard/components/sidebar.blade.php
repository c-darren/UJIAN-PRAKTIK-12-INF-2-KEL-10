@section('sidebar')
<aside id="sidebar" class="fixed top-0 left-0 z-20 flex flex-col flex-shrink-0 hidden w-64 h-full pt-16 font-normal duration-75 lg:flex transition-width" aria-label="Sidebar">
    <div class="relative flex flex-col flex-1 min-h-0 pt-0 bg-white border-r border-gray-200 dark:bg-gray-800 dark:border-gray-700">
        <div class="flex flex-col flex-1 pt-5 pb-4 overflow-y-auto">
            <div class="flex-1 px-3 space-y-1 bg-white divide-y divide-gray-200 dark:bg-gray-800 dark:divide-gray-700">
                <ul class="pb-2 space-y-2">
                    <li>
                        <a href="{{ route('dashboard') }}"
                        class="flex items-center p-2 text-base text-gray-900 rounded-lg hover:bg-gray-100 group dark:text-gray-200 dark:hover:bg-gray-700 
                        {{ request()->routeIs('dashboard') ? 'bg-gray-100 dark:bg-gray-700' : '' }}">
                            <svg class="w-6 h-6 text-gray-500 transition duration-75 group-hover:text-gray-900 dark:text-gray-400 dark:group-hover:text-white" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M2 10a8 8 0 018-8v8h8a8 8 0 11-16 0z"></path><path d="M12 2.252A8.014 8.014 0 0117.748 8H12V2.252z"></path></svg>
                            <span class="ml-3" sidebar-toggle-item>Dashboard</span>
                        </a>
                    </li>

                    @if (in_array(Auth::user()->role_id, [1]))
                        <!-- Authentication Dropdown -->
                        <button type="button" class="flex items-center w-full p-2 text-base text-gray-900 transition duration-75 rounded-lg group hover:bg-gray-100 dark:text-gray-200 dark:hover:bg-gray-700" aria-controls="dropdown-auth" data-collapse-toggle="dropdown-auth" {{ (request()->segment(2) === 'user') || (request()->segment(2) === 'role') ? 'aria-expanded="true"' : 'aria-expanded="false"' }}>
                            <svg class="flex-shrink-0 w-6 h-6 text-gray-500 transition duration-75 group-hover:text-gray-900 dark:text-gray-400 dark:group-hover:text-white" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"></path></svg>
                            <span class="flex-1 ml-3 text-left whitespace-nowrap" sidebar-toggle-item>Authentication</span>
                            <svg sidebar-toggle-item class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                        </button>
                        <ul id="dropdown-auth" class="{{ (request()->segment(2) === 'user') || (request()->segment(2) === 'role') ? '' : 'hidden' }} py-2 space-y-2">
                            <li>
                                <a href="{{ route('admin.authentication.roles.view') }}" class="flex items-center p-2 text-base text-gray-900 transition duration-75 rounded-lg pl-11 group hover:bg-gray-100 dark:text-gray-200 dark:hover:bg-gray-700
                                {{ request()->segment(2) === 'role' ? 'bg-gray-100 dark:bg-gray-700' : '' }}
                                ">Role</a>
                            </li>
                            <li>
                                <a href="{{ route('admin.authentication.users.view') }}" class="flex items-center p-2 text-base text-gray-900 transition duration-75 rounded-lg pl-11 group hover:bg-gray-100 dark:text-gray-200 dark:hover:bg-gray-700
                                {{ request()->segment(2) === 'user' ? 'bg-gray-100 dark:bg-gray-700' : '' }}
                                ">Users</a>
                            </li>
                        </ul>
                    @endif

                    @if (in_array(Auth::user()->role_id, [1,2]))
                        <!-- school Dropdown -->
                        <button type="button" class="flex items-center w-full p-2 text-base text-gray-900 transition duration-75 rounded-lg group hover:bg-gray-100 dark:text-gray-200 dark:hover:bg-gray-700" aria-controls="dropdown-academic_year" data-collapse-toggle="dropdown-academic_year" {{ (request()->segment(2) === 'academic_year') ? 'aria-expanded="true"' : 'aria-expanded="false"' }}>
                            <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                                <path fill-rule="evenodd" d="M11 4.717c-2.286-.58-4.16-.756-7.045-.71A1.99 1.99 0 0 0 2 6v11c0 1.133.934 2.022 2.044 2.007 2.759-.038 4.5.16 6.956.791V4.717Zm2 15.081c2.456-.631 4.198-.829 6.956-.791A2.013 2.013 0 0 0 22 16.999V6a1.99 1.99 0 0 0-1.955-1.993c-2.885-.046-4.76.13-7.045.71v15.081Z" clip-rule="evenodd"/>
                            </svg>
                            <span class="flex-1 ml-3 text-left whitespace-nowrap" sidebar-toggle-item>School</span>
                            <svg sidebar-toggle-item class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                            </svg>
                        </button>
                        <ul id="dropdown-academic_year" class="{{ (request()->segment(2) === 'academic_year') ? '' : 'hidden' }} py-2 space-y-2">
                            <li>
                                <a href="{{ route('school.academicYear.view') }}" class="flex items-center p-2 text-base text-gray-900 transition duration-75 rounded-lg pl-11 group hover:bg-gray-100 dark:text-gray-200 dark:hover:bg-gray-700
                                {{ request()->segment(2) === 'academic_year' ? 'bg-gray-100 dark:bg-gray-700' : '' }}
                                ">Academic Year</a>
                            </li>
                            <li>
                                <a href="{{ route('classroom.subject.view') }}" class="flex items-center p-2 text-base text-gray-900 transition duration-75 rounded-lg pl-11 group hover:bg-gray-100 dark:text-gray-200 dark:hover:bg-gray-700
                                {{ request()->segment(2) === 'subject' ? 'bg-gray-100 dark:bg-gray-700' : '' }}
                                ">Subject</a>
                            </li>
                        </ul>
                    @endif
                    
                    @if (in_array(Auth::user()->role_id, [1]))
                        <!-- Master Class Dropdown -->
                        <button type="button" class="flex items-center w-full p-2 text-base text-gray-900 transition duration-75 rounded-lg group hover:bg-gray-100 dark:text-gray-200 dark:hover:bg-gray-700" aria-controls="dropdown-masterClass" data-collapse-toggle="dropdown-masterClass" {{ (request()->segment(2) === 'masterClass') ? 'aria-expanded="true"' : 'aria-expanded="false"' }}>
                            <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                                <path fill-rule="evenodd" d="M3 6a2 2 0 0 1 2-2h5.532a2 2 0 0 1 1.536.72l1.9 2.28H3V6Zm0 3v10a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V9H3Z" clip-rule="evenodd"/>
                            </svg>                                                       
                            <span class="flex-1 ml-3 text-left whitespace-nowrap" sidebar-toggle-item>Classroom</span>
                            <svg sidebar-toggle-item class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                            </svg>
                        </button>
                        <ul id="dropdown-masterClass" class="{{ (request()->segment(2) === 'masterClass') ? '' : 'hidden' }} space-y-2">
                            <li>
                                <a href="{{ route('classroom.masterClass.view') }}" class="flex items-center p-2 text-base text-gray-900 transition duration-75 rounded-lg pl-11 group hover:bg-gray-100 dark:text-gray-200 dark:hover:bg-gray-700
                                {{ (request()->segment(2) === 'masterClass' && request()->segment(3) == 'view') ? 'bg-gray-100 dark:bg-gray-700' : '' }}
                                ">
                                Master Class</a>
                            </li>
                        </ul>
                        @if(isset($masterClass_name) && $masterClass_name != null)
                            <ul id="dropdown-masterClass" class="{{ (request()->segment(2) === 'masterClass' && request()->segment(3) === 'manage') ? '' : 'hidden'}}">
                                <li>
                                    <a href="{{ route('classroom.masterClass.manage.index', [$masterClass_id]) }}" class="flex items-center p-2 text-base text-gray-900 transition duration-75 rounded-lg pl-11 group hover:bg-yellow-200 dark:text-gray-200 dark:hover:bg-yellow-700
                                    {{ request()->segment(2) === 'masterClass' ? 'bg-yellow-100 dark:bg-yellow-600' : '' }}
                                    ">
                                    {{ $masterClass_name }}</a>
                                </li>
                            </ul>
                            @if(isset($masterClass_name) && $masterClass_name != null && isset($classList->class_name) && $classList->class_name != null)
                                <ul id="dropdown-masterClass" class="{{ (request()->segment(2) === 'masterClass' && request()->segment(3) === 'manage') ? '' : 'hidden'}}">
                                    <li>
                                        <a href="{{ route('class_lists.teacher.index', [$masterClass_id, $classList_id]) }}" class="flex items-center p-2 text-base text-gray-900 transition duration-75 rounded-lg pl-11 group hover:bg-blue-200 dark:text-blue-200 dark:hover:bg-blue-700
                                        {{ request()->segment(2) === 'masterClass' ? 'bg-blue-100 dark:bg-blue-700' : '' }}
                                        ">
                                        {{ $classList->class_name }}</a>
                                    </li>
                                </ul>
                            @endif
                        @endif
                    @endif

                    @if(Auth::user()->role_id == 2)
                    <!-- Teacher Class Dropdown -->           
                        @if(isset($masterClass_id) && $masterClass_id != null && isset($classList->id) && $classList->id != null)
                            <button type="button" class="flex items-center w-full p-2 text-base text-gray-900 transition duration-75 rounded-lg group hover:bg-gray-100 dark:text-gray-200 dark:hover:bg-gray-700" aria-controls="dropdown-classroom" data-collapse-toggle="dropdown-classroom" {{ (request()->segment(1) === 'classroom') ? 'aria-expanded="true"' : 'aria-expanded="false"' }}>
                                <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                                    <path fill-rule="evenodd" d="M3 6a2 2 0 0 1 2-2h5.532a2 2 0 0 1 1.536.72l1.9 2.28H3V6Zm0 3v10a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V9H3Z" clip-rule="evenodd"/>
                                </svg>                                                       
                                <span class="flex-1 ml-3 text-left whitespace-nowrap" sidebar-toggle-item>{{ Str::limit($classList->class_name, 17) }}</span>
                                <svg sidebar-toggle-item class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                </svg>
                            </button>
                            <ul id="dropdown-classroom" class="{{ request()->segment(1) === 'classroom' ? '' : 'hidden'}}">
                                <li>
                                    <a href="{{ route('classroom.index', [$masterClass_id, $classList->id]) }}" class="flex items-center p-1 text-base text-gray-900 transition duration-75 rounded-lg pl-11 group hover:bg-blue-200 dark:text-white dark:hover:bg-blue-700
                                    {{ (request()->segment(1) === 'classroom' && request()->segment(4) == '') ? 'bg-blue-100 dark:bg-blue-700' : '' }}
                                    ">
                                    Class Info</a>
                                </li>
                                <li>
                                    <a href="{{ route('classroom.teacher.index', [$masterClass_id, $classList->id]) }}" class="flex items-center mt-2 p-1 text-base text-gray-900 transition duration-75 rounded-lg pl-11 group hover:bg-blue-200 dark:text-white dark:hover:bg-blue-700
                                    {{ (request()->segment(1) === 'classroom' && request()->segment(4) === 'teacher') ? 'bg-blue-100 dark:bg-blue-700' : '' }}
                                    ">
                                    Teachers</a>
                                </li>
                                <li>
                                    <a href="{{ route('classroom.student.index', [$masterClass_id, $classList->id]) }}" class="flex items-center mt-2 p-1 text-base text-gray-900 transition duration-75 rounded-lg pl-11 group hover:bg-blue-200 dark:text-white dark:hover:bg-blue-700
                                    {{ (request()->segment(1) === 'classroom' && request()->segment(4) === 'student') ? 'bg-blue-100 dark:bg-blue-700' : '' }}
                                    ">
                                    Students</a>
                                </li>
                                <li>
                                    <a href="{{ route('classroom.topic.index', [$masterClass_id, $classList->id]) }}" class="flex items-center mt-2 p-1 text-base text-gray-900 transition duration-75 rounded-lg pl-11 group hover:bg-blue-200 dark:text-white dark:hover:bg-blue-700
                                    {{ (request()->segment(1) === 'classroom' && request()->segment(4) === 'topic') ? 'bg-blue-100 dark:bg-blue-700' : '' }}
                                    ">
                                    Topic</a>
                                </li>
                                <li>
                                    <a href="{{ route('classroom.attendance.index', [$masterClass_id, $classList->id]) }}" class="flex items-center mt-2 p-1 text-base text-gray-900 transition duration-75 rounded-lg pl-11 group hover:bg-blue-200 dark:text-white dark:hover:bg-blue-700
                                    {{ (request()->segment(1) === 'classroom' && request()->segment(4) === 'attendance') ? 'bg-blue-100 dark:bg-blue-700' : '' }}
                                    ">
                                    Daftar Hadir</a>
                                </li>
                                <li>
                                    <a href="{{ route('classroom.resources.index', [$masterClass_id, $classList->id]) }}" class="flex items-center mt-2 p-1 text-base text-gray-900 transition duration-75 rounded-lg pl-11 group hover:bg-blue-200 dark:text-white dark:hover:bg-blue-700
                                    {{ (request()->segment(1) === 'classroom' && request()->segment(4) == 'resources') ? 'bg-blue-100 dark:bg-blue-700' : '' }}
                                    ">
                                    Tugas & Materi</a>
                                </li>
                            </ul>
                        @endif
                    @endif
                </ul>
            </div>
        </div>
        <div class="absolute bottom-0 left-0 justify-center hidden w-full p-4 space-x-4 bg-white lg:flex dark:bg-gray-800" sidebar-bottom-menu>
        </div>
    </div>
</aside>
@endsection