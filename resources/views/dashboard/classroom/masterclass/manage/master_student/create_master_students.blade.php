@section('title', $page_title)
<div class="w-full bg-white rounded-lg shadow dark:border dark:bg-gray-800 dark:border-gray-700 px-6 py-8">
    <div class="flex flex-col items-center">
        @if($records == 'Invalid')
        <div class="flex flex-col items-center">
            <p class="font-bold text-red-500">You can't add more students, please update academic year and master class to active</p>
        </div>

        @else
        <h2 class="text-xl font-bold text-gray-900 dark:text-white">Add New Student</h2>
            <div id="addFormView" class="w-full">
                <form id="addForm" x-ref="addForm">
                    @csrf
                    <input type="number" name="master_class_id" value="{{ $masterClass_id }}" hidden id="master_class_id">
                    <div class="grid gap-4 sm:grid-cols-2 sm:gap-6">
                        <div class="sm:col-span-2 relative">
                            <label for="student_name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">User ID</label>
                            <input list="non_enrolled_student_name" 
                            class="block py-2.5 px-0 w-full text-sm text-gray-500 bg-transparent border-0 border-b-2 border-gray-200 appearance-none dark:text-gray-400 dark:border-gray-700 focus:outline-none focus:ring-0 focus:border-gray-200 peer"
                            id="student_name"
                            placeholder="Type User ID"
                            name="user_id">
                            <datalist id="non_enrolled_student_name">
                                @foreach ($records as $record)
                                    <option value="{{ $record->id }} - {{ $record->name }}" data-id="{{ $record->id }}"></option>
                                @endforeach
                            </datalist>
                        </div>
                    </div>

                    <div class="flex justify-between mt-4 sm:mt-6">
                        <button type="submit" id="submit_form" class="rounded-full inline-flex items-center px-5 py-2.5 text-sm font-medium text-white bg-blue-600 focus:ring-4 focus:ring-blue-200 dark:focus:ring-blue-900 hover:bg-blue-700">
                            Add Student
                        </button>
                        <button type="reset" class="rounded-full inline-flex items-center px-5 py-2.5 text-sm font-medium text-white bg-red-700 focus:ring-4 focus:ring-red-200 dark:focus:ring-red-900 hover:bg-red-800">
                            Reset
                        </button>
                    </div>
                </form>
            </div>
        @endif
    </div>
</div> 
@section('required_scripts')
<script type="text/javascript" src="{{ asset('js/classroom/manage_master_class/master_class_students/student_add.js') }}"></script>
@endsection