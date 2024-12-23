@section('page_content')

<div x-data="materialTable(),{
    $store: { 
        createModal: { open: false },
        CreateAssignmentModal: { open: false },
        deleteMaterialModal: { open: false }
    },
    open: false, 
    data: {}, 
    showModal(tableData) { 
        this.data = tableData; 
        this.open = true; 
    }
    }"
    @keydown.escape.window="open = false"
    class="container mx-auto p-4">
    <!-- Pencarian -->

    @livewire('classroom.teacher-resource-table', ['masterClass_id' => $masterClass_id, 'classList' => $classList, 'topics' => $topics])
    @include('dashboard.classroom.class_list.resource.create_material')
    @include('dashboard.classroom.class_list.resource.create_assignment')

    @include('dashboard.classroom.class_list.resource.delete')
</div>
@endsection

@section('required_scripts')
    <script type="text/javascript" src="{{ asset('js/classroom/class_list/resource/modal_init.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/classroom/class_list/resource/delete_modal.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/classroom/class_list/resource/create_assignments.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/classroom/class_list/resource/material.js') }}"></script>
@endsection