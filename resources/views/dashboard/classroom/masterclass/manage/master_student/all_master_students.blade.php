@section('title', $page_title)

<div x-data="{
    $store: { 
        deleteModal: { open: false }
    },
    open: false, 
    showModal() { 
        this.open = true; 
    }
    }"
    @keydown.escape.window="open = false"
    class="relative">
    <div class="flex flex-col py-1 mt-2">
        <div class="overflow-x-auto">
            <div class="inline-block min-w-full align-middle">
                @livewire('classroom.masterclassstudents-table', ['masterClass_id' => $masterClass_id])
            </div>
        </div>
    </div>
    <!-- Delete Modal -->
    @include('dashboard.classroom.masterclass.manage.master_student.delete_modal')
</div>

@section('required_scripts')
<script type="text/javascript" src="{{ asset('js/classroom/manage_master_class/master_class_students/student_delete.js') }}"></script>
@endsection
