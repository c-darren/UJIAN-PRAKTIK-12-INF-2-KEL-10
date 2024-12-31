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

    @livewire('classroom.student-resource-table', ['masterClass_id' => $masterClass_id, 'classList' => $classList, 'topics' => $topics])
</div>
@endsection

@section('required_scripts')
    <script type="text/javascript" src="{{ asset('js/classroom/student/resource/grid_view.js') }}"></script>
@endsection